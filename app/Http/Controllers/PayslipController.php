<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payslip;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Services\PayrollCalculatorService;
use App\Notifications\DraftPayslipNotification;
use App\Notifications\PayslipApprovedNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\FonnteService;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Payslip::class);
        
        $user = Auth::user();
        if (in_array(strtolower($user->role), ['superadmin', 'admin', 'finance'])) {
            $payslips = Payslip::with('employee.user')->latest()->get();
        } else if (strtolower($user->role) === 'hr') {
            $payslips = Payslip::with('employee.user')->whereHas('employee.user', function($q) {
                $q->whereIn('role', ['employee', 'finance']);
            })->latest()->get();
        } else {
            $employee = $user->employee;
            if (!$employee) {
                $payslips = collect();
            } else {
                $payslips = Payslip::where('employee_id', $employee->id)->latest()->get();
            }
        }

        return view('payslip.index', [
            'title' => 'Data Payslip',
            'payslips' => $payslips
        ]);
    }

    public function create()
    {
        return view('payslip.create', [
            'title' => 'Generate Payslip'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string', // e.g. 10-2023
        ]);

        try {
            DB::beginTransaction();

            $period = $request->period;
            $employees = Employee::with(['salaryStructures', 'allowances', 'deductions'])->get();

        $generatedCount = 0;
        $calculator = new PayrollCalculatorService();

        foreach ($employees as $emp) {
            $parts = explode('-', $period);
            if (count($parts) !== 2) continue;
            
            $month = $parts[0];
            $year = $parts[1];
            
            $calc = $calculator->calculate($emp, $month, $year);
            
            Payslip::updateOrCreate(
                [
                    'employee_id' => $emp->id,
                    'period' => $period,
                ],
                array_merge([
                    'status' => 'draft',
                    'payment_date' => now()->toDateString(),
                ], $calc)
            );
            $generatedCount++;
        }

            DB::commit();

            // Notify Admin & Superadmin
            $notifyUsers = User::whereIn(DB::raw('LOWER(role)'), ['superadmin', 'admin'])->get();
            Notification::send($notifyUsers, new DraftPayslipNotification($period));

            return redirect()->route('payslips.index')->with('success', "Successfully generated {$generatedCount} payslips for period {$period}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses slip gaji: ' . $e->getMessage());
        }
    }

    public function show(Payslip $payslip)
    {
        Gate::authorize('view', $payslip);

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        $taxRecord = \App\Models\TaxRecord::where('employee_id', $payslip->employee_id)
            ->where('period', $payslip->period)
            ->first();

        // Calculate Alpha Deduction & Overtime exactly as stored
        $parts = explode('-', $payslip->period);
        $alphaCount = 0;
        $overtimeHours = 0;
        if (count($parts) == 2) {
            $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->where('status', 'Alpa')
                ->count();
            $overtimeHours = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->sum('overtime_hours');
        }
        $basicSalary = $payslip->employee?->salaryStructures->sortByDesc('effective_date')->first()?->basic_salary ?? 0;
        $workingDays = config('payroll.working_days_per_month', 22);
        $alphaDeduction = ($basicSalary / $workingDays) * $alphaCount;
        $overtimePay = ($basicSalary / 173) * $overtimeHours;
        
        return view('payslip.show', [
            'title' => 'Detail Payslip',
            'payslip' => $payslip,
            'taxRecord' => $taxRecord,
            'alphaCount' => $alphaCount,
            'alphaDeduction' => $alphaDeduction,
            'overtimeHours' => $overtimeHours,
            'overtimePay' => $overtimePay
        ]);
    }

    public function print(Payslip $payslip)
    {
        Gate::authorize('view', $payslip);

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        $taxRecord = \App\Models\TaxRecord::where('employee_id', $payslip->employee_id)
            ->where('period', $payslip->period)
            ->first();

        // Calculate Alpha Deduction & Overtime exactly as stored
        $parts = explode('-', $payslip->period);
        $alphaCount = 0;
        $overtimeHours = 0;
        if (count($parts) == 2) {
            $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->where('status', 'Alpa')
                ->count();
            $overtimeHours = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->sum('overtime_hours');
        }
        $basicSalary = $payslip->employee?->salaryStructures->sortByDesc('effective_date')->first()?->basic_salary ?? 0;
        $workingDays = config('payroll.working_days_per_month', 22);
        $alphaDeduction = ($basicSalary / $workingDays) * $alphaCount;
        $overtimePay = ($basicSalary / 173) * $overtimeHours;
        
        return view('payslip.pdf', [
            'title' => 'Cetak Payslip',
            'payslip' => $payslip,
            'taxRecord' => $taxRecord,
            'alphaCount' => $alphaCount,
            'alphaDeduction' => $alphaDeduction,
            'overtimeHours' => $overtimeHours,
            'overtimePay' => $overtimePay
        ]);
    }

    public function approveAll(Request $request)
    {
        if (!in_array(strtolower(Auth::user()->role), ['superadmin', 'admin'])) {
            abort(403, 'Hanya Admin atau Superadmin yang bisa menyetujui slip gaji.');
        }

        $payslips = Payslip::where('status', 'draft')->with('employee.user')->get();
        if ($payslips->isEmpty()) {
            return back()->with('error', 'Tidak ada slip gaji dengan status Draft untuk disetujui.');
        }

        $fonnte = new FonnteService();
        $approvedCount = 0;

        foreach ($payslips as $payslip) {
            $payslip->update(['status' => 'approved']);
            $approvedCount++;

            if ($payslip->employee && $payslip->employee->user) {
                $user = $payslip->employee->user;
                $user->notify(new PayslipApprovedNotification($payslip));

                if (!empty($user->phone)) {
                    $message = "Halo {$user->name}, Slip Gaji Anda untuk periode {$payslip->period} sudah diterbitkan.\nSilakan login ke sistem untuk melihat rinciannya:\n" . route('payslips.show', $payslip->id);
                    try {
                        $fonnte->sendMessage($user->phone, $message);
                    } catch (\Exception $e) {
                        // ignore fonnte error to continue approving others
                    }
                }
            }
        }

        return back()->with('success', "Berhasil menyetujui {$approvedCount} slip gaji secara massal. Notifikasi telah dikirimkan.");
    }

    public function approve(Payslip $payslip)
    {
        // Only superadmin and admin can approve
        if (!in_array(strtolower(Auth::user()->role), ['superadmin', 'admin'])) {
            abort(403, 'Hanya Admin atau Superadmin yang bisa menyetujui slip gaji.');
        }

        if ($payslip->status === 'approved') {
            return back()->with('error', 'Slip Gaji ini sudah berstatus Approved.');
        }

        $payslip->update(['status' => 'approved']);

        // Notify Employee via DB Notification
        if ($payslip->employee && $payslip->employee->user) {
            $user = $payslip->employee->user;
            $user->notify(new PayslipApprovedNotification($payslip));

            // Kirim WhatsApp (Fonnte API)
            if (!empty($user->phone)) {
                $fonnte = new FonnteService();
                $message = "Halo {$user->name}, Slip Gaji Anda untuk periode {$payslip->period} sudah diterbitkan.\nSilakan login ke sistem untuk melihat rinciannya:\n" . route('payslips.show', $payslip->id);
                $fonnte->sendMessage($user->phone, $message);
            }
        }

        return back()->with('success', 'Slip Gaji berhasil disetujui (Approved). Notifikasi dikirimkan.');
    }

    public function verify(Payslip $payslip)
    {
        // Check if payslip is valid and approved/paid
        if (!in_array($payslip->status, ['approved', 'paid'])) {
            abort(404, 'Slip Gaji tidak ditemukan atau belum disetujui.');
        }

        $payslip->load(['employee.user']);

        return view('payslip.verify', [
            'title' => 'Verifikasi Slip Gaji',
            'payslip' => $payslip
        ]);
    }

    public function exportExcel()
    {
        \Illuminate\Support\Facades\Gate::authorize('viewAny', Payslip::class);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PayslipsExport, 'data-slip-gaji.xlsx');
    }
}
