<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalaryStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            \App\Models\SalaryStructure::create([
                'employee_id' => $employee->id,
                'basic_salary' => 8000000,
                'effective_date' => '2023-01-01',
            ]);
        }
        
        // Generate for all other employees
        $otherEmployees = \App\Models\Employee::where('employee_number', '!=', 'EMP-001')->get();
        foreach ($otherEmployees as $emp) {
            \App\Models\SalaryStructure::factory()->create([
                'employee_id' => $emp->id,
            ]);
        }
    }
}
