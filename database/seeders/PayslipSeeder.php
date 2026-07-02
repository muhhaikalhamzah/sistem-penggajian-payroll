<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payslip;
use App\Models\Employee;

class PayslipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::first();

        if ($employee) {
            Payslip::create([
                'employee_id' => $employee->id,
                'period' => '10-2023',
                'gross_salary' => 8500000,
                'total_deductions' => 150000,
                'net_salary' => 8350000,
                'payment_date' => '2023-10-28',
            ]);
        }
    }
}
