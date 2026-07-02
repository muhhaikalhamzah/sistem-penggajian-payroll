<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            \App\Models\Deduction::create([
                'employee_id' => $employee->id,
                'name' => 'Potongan BPJS Kesehatan',
                'amount' => 150000,
                'type' => 'Tetap',
            ]);
        }
        
        $otherEmployees = \App\Models\Employee::where('employee_number', '!=', 'EMP-001')->get();
        foreach ($otherEmployees as $emp) {
            \App\Models\Deduction::factory(1)->create([
                'employee_id' => $emp->id,
            ]);
        }
    }
}
