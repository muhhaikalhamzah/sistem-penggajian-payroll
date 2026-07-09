<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GeneratePayslipJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle(\App\Services\PayrollCalculatorService $calculator): void
    {
        $employees = \App\Models\Employee::all();
        $month = date('m');
        $year = date('Y');
        
        foreach($employees as $emp) {
            // Cek apakah bulan ini sudah digenerate
            $exists = \App\Models\Payslip::where('employee_id', $emp->id)
                        ->where('period', $month . '-' . $year)
                        ->exists();
            if ($exists) {
                continue;
            }
            
            $calc = $calculator->calculate($emp, $month, $year);
            \App\Models\Payslip::create(array_merge([
                'employee_id' => $emp->id,
                'period' => $month . '-' . $year,
                'status' => 'draft',
                'payment_date' => date('Y-m-25')
            ], $calc));
        }
    }
}
