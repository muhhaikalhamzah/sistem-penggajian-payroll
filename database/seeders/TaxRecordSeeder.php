<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            \App\Models\TaxRecord::create([
                'employee_id' => $employee->id,
                'taxable_income' => 5000000,
                'pph21_amount' => 25000,
                'period' => '10-2023'
            ]);
        }

        // Random other tax records
        \App\Models\TaxRecord::factory(10)->create();
    }
}
