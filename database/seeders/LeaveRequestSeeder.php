<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            \App\Models\LeaveRequest::create([
                'employee_id' => $employee->id,
                'start_date' => '2023-11-01',
                'end_date' => '2023-11-03',
                'leave_type' => 'Tahunan',
                'status' => 'Menunggu',
            ]);
            
            \App\Models\LeaveRequest::create([
                'employee_id' => $employee->id,
                'start_date' => '2023-10-15',
                'end_date' => '2023-10-15',
                'leave_type' => 'Sakit',
                'status' => 'Disetujui',
            ]);
        }
        
        // Generate random requests for others
        $otherEmployees = \App\Models\Employee::where('employee_number', '!=', 'EMP-001')->get();
        foreach ($otherEmployees as $emp) {
            \App\Models\LeaveRequest::factory(2)->create([
                'employee_id' => $emp->id,
            ]);
        }
    }
}
