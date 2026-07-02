<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = \App\Models\Employee::where('employee_number', 'EMP-001')->first();
        if ($employee) {
            for ($day = 1; $day <= 31; $day++) {
                $date = '2023-10-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                // Skip weekends
                $isWeekend = date('N', strtotime($date)) >= 6;
                if ($isWeekend) continue;

                \App\Models\AttendanceRecord::create([
                    'employee_id' => $employee->id,
                    'record_date' => $date,
                    'check_in' => '08:00',
                    'check_out' => '17:00',
                    'overtime_hours' => 0,
                    'status' => 'Hadir',
                ]);
            }
        }
        
        // Generate random attendance for others
        $otherEmployees = \App\Models\Employee::where('employee_number', '!=', 'EMP-001')->get();
        foreach ($otherEmployees as $emp) {
            for ($day = 1; $day <= 31; $day++) {
                $date = '2023-10-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                $isWeekend = date('N', strtotime($date)) >= 6;
                if ($isWeekend) continue;

                \App\Models\AttendanceRecord::create([
                    'employee_id' => $emp->id,
                    'record_date' => $date,
                    'check_in' => '08:00',
                    'check_out' => '17:00',
                    'overtime_hours' => 0,
                    'status' => 'Hadir',
                ]);
            }
        }
    }
}
