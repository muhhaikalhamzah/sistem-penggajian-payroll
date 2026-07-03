<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class PayslipController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (in_array($user->role, ['finance', 'hr', 'Superadmin', 'Admin'])) {
            $payslips = Payslip::with('employee.user')->latest()->get();
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

        $period = $request->period;
        $employees = Employee::with(['salaryStructures', 'allowances', 'deductions'])->get();

        $generatedCount = 0;
        $workingDays = config('payroll.working_days_per_month', 22);

        foreach ($employees as $emp) {


            $basicSalary = $emp->salaryStructures->sortByDesc('effective_date')->first()->basic_salary ?? 0;
            $totalAllowances = $emp->allowances->sum('amount');
            $grossSalary = $basicSalary + $totalAllowances;

            // Calculate Alpha Deduction
            $parts = explode('-', $period);
            $alphaCount = 0;
            if(count($parts) == 2) {
                $dbMonth = $parts[0];
                $dbYear = $parts[1];
                $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $emp->id)
                    ->whereMonth('record_date', $dbMonth)
                    ->whereYear('record_date', $dbYear)
                    ->where('status', 'Alpa')
                    ->count();
            }
            $alphaDeduction = ($basicSalary / $workingDays) * $alphaCount;
            
            $baseDeductions = $emp->deductions->sum('amount') + $alphaDeduction;

            // Calculate PPh21
            $biayaJabatan = $grossSalary * 0.05;
            if ($biayaJabatan > 500000) $biayaJabatan = 500000;

            $nettoBulanan = $grossSalary - $biayaJabatan; // base deduction not subtracted from tax logic usually, but let's keep it simple standard
            $nettoTahunan = $nettoBulanan * 12;

            $ptkp = 54000000; // default TK/0
            switch($emp->ptkp_status) {
                case 'K/0': $ptkp = 58500000; break;
                case 'K/1': $ptkp = 63000000; break;
                case 'K/2': $ptkp = 67500000; break;
                case 'K/3': $ptkp = 72000000; break;
            }

            $pkpTahunan = $nettoTahunan - $ptkp;
            if ($pkpTahunan < 0) $pkpTahunan = 0;

            $pph21Tahunan = 0;
            if ($pkpTahunan > 0) {
                if ($pkpTahunan <= 60000000) {
                    $pph21Tahunan = $pkpTahunan * 0.05;
                } elseif ($pkpTahunan <= 250000000) {
                    $pph21Tahunan = (60000000 * 0.05) + (($pkpTahunan - 60000000) * 0.15);
                } elseif ($pkpTahunan <= 500000000) {
                    $pph21Tahunan = (60000000 * 0.05) + (190000000 * 0.15) + (($pkpTahunan - 250000000) * 0.25);
                } else {
                    $pph21Tahunan = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (($pkpTahunan - 500000000) * 0.30);
                }
            }

            $pph21Bulanan = $pph21Tahunan / 12;

            $taxRecord = \App\Models\TaxRecord::updateOrCreate(
                ['employee_id' => $emp->id, 'period' => $period],
                [
                    'taxable_income' => $pkpTahunan,
                    'pph21_amount' => $pph21Bulanan,
                ]
            );

            $totalDeductions = $baseDeductions + $pph21Bulanan;
            $netSalary = $grossSalary - $totalDeductions;

            Payslip::updateOrCreate(
                [
                    'employee_id' => $emp->id,
                    'period' => $period,
                ],
                [
                    'gross_salary' => $grossSalary,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                    'payment_date' => now()->toDateString(),
                ]
            );
            $generatedCount++;
        }

        return redirect()->route('payslips.index')->with('success', "Successfully generated {$generatedCount} payslips for period {$period}.");
    }

    public function show(Payslip $payslip)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['finance', 'hr', 'Superadmin', 'Admin'])) {
            if (!$user->employee || $payslip->employee_id !== $user->employee->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        $taxRecord = \App\Models\TaxRecord::where('employee_id', $payslip->employee_id)
            ->where('period', $payslip->period)
            ->first();

        // Calculate Alpha Deduction exactly as stored
        $parts = explode('-', $payslip->period);
        $alphaCount = 0;
        if (count($parts) == 2) {
            $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->where('status', 'Alpa')
                ->count();
        }
        $basicSalary = $payslip->employee->salaryStructures->sortByDesc('effective_date')->first()->basic_salary ?? 0;
        $workingDays = config('payroll.working_days_per_month', 22);
        $alphaDeduction = ($basicSalary / $workingDays) * $alphaCount;
        
        return view('payslip.show', [
            'title' => 'Detail Payslip',
            'payslip' => $payslip,
            'taxRecord' => $taxRecord,
            'alphaCount' => $alphaCount,
            'alphaDeduction' => $alphaDeduction
        ]);
    }

    public function print(Payslip $payslip)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['finance', 'hr', 'Superadmin', 'Admin'])) {
            if (!$user->employee || $payslip->employee_id !== $user->employee->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        $taxRecord = \App\Models\TaxRecord::where('employee_id', $payslip->employee_id)
            ->where('period', $payslip->period)
            ->first();

        // Calculate Alpha Deduction exactly as stored
        $parts = explode('-', $payslip->period);
        $alphaCount = 0;
        if (count($parts) == 2) {
            $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
                ->whereMonth('record_date', $parts[0])
                ->whereYear('record_date', $parts[1])
                ->where('status', 'Alpa')
                ->count();
        }
        $basicSalary = $payslip->employee->salaryStructures->sortByDesc('effective_date')->first()->basic_salary ?? 0;
        $workingDays = config('payroll.working_days_per_month', 22);
        $alphaDeduction = ($basicSalary / $workingDays) * $alphaCount;
        
        return view('payslip.pdf', [
            'title' => 'Cetak Payslip',
            'payslip' => $payslip,
            'taxRecord' => $taxRecord,
            'alphaCount' => $alphaCount,
            'alphaDeduction' => $alphaDeduction
        ]);
    }
}
