<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            \App\Models\Allowance::create([
                'employee_id' => $employee->id,
                'name' => 'Tunjangan Transport',
                'amount' => 500000,
                'type' => 'Fixed',
            ]);
            
            \App\Models\Allowance::create([
                'employee_id' => $employee->id,
                'name' => 'Tunjangan Makan',
                'amount' => 40000,
                'type' => 'Variable',
            ]);
        }
        
        $otherEmployees = \App\Models\Employee::where('employee_number', '!=', 'EMP-001')->get();
        foreach ($otherEmployees as $emp) {
            \App\Models\Allowance::factory(2)->create([
                'employee_id' => $emp->id,
            ]);
        }
    }
}
