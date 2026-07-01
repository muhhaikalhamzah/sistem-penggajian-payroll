<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Department::where('name', 'IT')->first();
        $pos = Position::where('title', 'Staff')->first();
        $user = User::where('role', 'employee')->first(); // User dummy dari UserSeeder

        if ($dept && $pos) {
            Employee::create([
                'employee_number' => 'EMP-001',
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'ptkp_status' => 'TK/0',
                'join_date' => '2023-01-15',
                'department_id' => $dept->id,
                'position_id' => $pos->id,
                'user_id' => $user ? $user->id : null,
            ]);
        }
        
        // Generate additional random employees via factory
        Employee::factory(10)->create();
    }
}
