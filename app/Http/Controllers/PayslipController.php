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
        if ($user->hasRole('finance') || $user->hasRole('Superadmin')) {
            $payslips = Payslip::with('employee.user')->latest()->get();
        } else {
            $employee = $user->employee;
            if (!$employee) {
                $payslips = collect();
            } else {
                $payslips = Payslip::where('employee_id', $employee->id)->latest()->get();
            }
        }

        return view('payslip.index', compact('payslips'));
    }

    public function create()
    {
        return view('payslip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|string', // e.g. 10-2023
        ]);

        $period = $request->period;
        $employees = Employee::with(['salaryStructures', 'allowances', 'deductions', 'taxRecords' => function($q) use ($period) {
            $q->where('period', $period);
        }])->get();

        $generatedCount = 0;

        foreach ($employees as $emp) {
            if (Payslip::where('employee_id', $emp->id)->where('period', $period)->exists()) {
                continue;
            }

            $basicSalary = $emp->salaryStructures->sortByDesc('effective_date')->first()->basic_salary ?? 0;
            $totalAllowances = $emp->allowances->sum('amount');
            $grossSalary = $basicSalary + $totalAllowances;

            $baseDeductions = $emp->deductions->sum('amount');
            $taxAmount = $emp->taxRecords->first()->pph21_amount ?? 0;
            
            $totalDeductions = $baseDeductions + $taxAmount;
            $netSalary = $grossSalary - $totalDeductions;

            Payslip::create([
                'employee_id' => $emp->id,
                'period' => $period,
                'gross_salary' => $grossSalary,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'payment_date' => now()->toDateString(),
            ]);
            $generatedCount++;
        }

        return redirect()->route('payslips.index')->with('success', "Successfully generated {$generatedCount} payslips for period {$period}.");
    }

    public function show(Payslip $payslip)
    {
        $user = Auth::user();
        if (!$user->hasRole('finance') && !$user->hasRole('Superadmin')) {
            if (!$user->employee || $payslip->employee_id !== $user->employee->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        return view('payslip.show', compact('payslip'));
    }

    public function print(Payslip $payslip)
    {
        $user = Auth::user();
        if (!$user->hasRole('finance') && !$user->hasRole('Superadmin')) {
            if (!$user->employee || $payslip->employee_id !== $user->employee->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
        
        return view('payslip.pdf', compact('payslip'));
    }
}
