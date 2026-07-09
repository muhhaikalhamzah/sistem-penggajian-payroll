<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\AttendanceRecord;
use App\Models\Payslip;
use App\Services\PayrollCalculatorService;
use Carbon\Carbon;
use Faker\Factory as Faker;

class DummyPayrollSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $password = Hash::make('password');

        // 1. Users (1 per role)
        $roles = ['Superadmin', 'Admin', 'hr', 'finance', 'employee'];
        $dummyUsers = [];
        foreach ($roles as $role) {
            $email = strtolower($role) . '_dummy@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'User ' . $role,
                    'password' => $password,
                    'role' => $role,
                    'email_verified_at' => now(),
                ]
            );
            $dummyUsers[$role] = $user;
        }

        // 2. Departments
        $depts = ['IT', 'Finance', 'HR', 'Marketing', 'Operations'];
        $departmentIds = [];
        foreach ($depts as $deptName) {
            $dept = Department::firstOrCreate(['name' => $deptName]);
            $departmentIds[] = $dept->id;
        }

        // 3. Positions
        $posData = [
            ['title' => 'Manager', 'min_salary' => 10000000, 'max_salary' => 20000000],
            ['title' => 'Supervisor', 'min_salary' => 7000000, 'max_salary' => 12000000],
            ['title' => 'Staff', 'min_salary' => 4000000, 'max_salary' => 8000000],
        ];
        $positionIds = [];
        foreach ($posData as $pd) {
            $pos = Position::firstOrCreate(['title' => $pd['title']], $pd);
            $positionIds[] = $pos->id;
        }

        // 4. Employees (15-20)
        $ptkpStatuses = ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'];
        $employees = [];

        for ($i = 1; $i <= 18; $i++) {
            $deptId = $faker->randomElement($departmentIds);
            $posId = $faker->randomElement($positionIds);
            
            // Assign a user_id only for a few
            $userId = null;
            if ($i == 1) $userId = $dummyUsers['employee']->id;
            elseif ($i == 2) $userId = $dummyUsers['hr']->id;

            $emp = Employee::create([
                'employee_number' => 'EMP' . date('Ym') . str_pad($i, 3, '0', STR_PAD_LEFT),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'ptkp_status' => $faker->randomElement($ptkpStatuses),
                'join_date' => $faker->dateTimeBetween('-3 years', '-1 months')->format('Y-m-d'),
                'department_id' => $deptId,
                'position_id' => $posId,
                'user_id' => $userId,
            ]);
            $employees[] = $emp;

            // 5. Salary Structure
            $pos = Position::find($posId);
            $basicSalary = $faker->randomFloat(2, $pos->min_salary, $pos->max_salary);
            SalaryStructure::create([
                'employee_id' => $emp->id,
                'basic_salary' => $basicSalary,
                'effective_date' => Carbon::parse($emp->join_date)->startOfMonth()->format('Y-m-d'),
            ]);

            // 6. Allowances & Deductions
            if (rand(1, 10) > 5) {
                Allowance::create([
                    'employee_id' => $emp->id,
                    'name' => 'Tunjangan Transport',
                    'amount' => 500000,
                    'type' => 'Tetap',
                ]);
            }
            if (rand(1, 10) > 7) {
                Deduction::create([
                    'employee_id' => $emp->id,
                    'name' => 'Potongan Koperasi',
                    'amount' => 100000,
                    'type' => 'Tetap',
                ]);
            }
        }

        // 7. Attendance Records (For 2 periods: last month and this month)
        $periods = [
            [now()->subMonth()->month, now()->subMonth()->year],
            [now()->month, now()->year]
        ];

        foreach ($employees as $emp) {
            foreach ($periods as $period) {
                $month = $period[0];
                $year = $period[1];
                $daysInMonth = Carbon::create($year, $month)->daysInMonth;

                // Generate attendance for working days (roughly 22 days)
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = Carbon::create($year, $month, $d);
                    if ($date->isWeekend()) continue;

                    $statusRand = rand(1, 100);
                    $status = 'Hadir';
                    $checkIn = '08:00';
                    $checkOut = '17:00';

                    if ($statusRand > 95) {
                        $status = 'Alpa';
                        $checkIn = null;
                        $checkOut = null;
                    } elseif ($statusRand > 90) {
                        $status = 'Cuti';
                        $checkIn = null;
                        $checkOut = null;
                    } elseif ($statusRand > 80) {
                        // Lembur
                        $checkOut = '19:00';
                    }

                    AttendanceRecord::create([
                        'employee_id' => $emp->id,
                        'record_date' => $date->format('Y-m-d'),
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'status' => $status,
                        // overtime_hours validation depends on controller usually, but here we just leave it 0 and let calculator use it?
                        // Wait, calculator uses overtime_hours column in table! Let's calculate it manually for seeder
                        'overtime_hours' => ($checkOut === '19:00') ? 2 : 0,
                    ]);
                }
            }
        }

        // 8. Generate Payslips using PayrollCalculatorService
        $calculator = new PayrollCalculatorService();
        foreach ($periods as $period) {
            $month = $period[0];
            $year = $period[1];
            $periodStr = str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $year;

            foreach ($employees as $emp) {
                // Calculator requires the employee to be re-fetched with relations if it uses them, 
                // but calculate() does its own queries.
                $calc = $calculator->calculate($emp, $month, $year);

                Payslip::updateOrCreate(
                    [
                        'employee_id' => $emp->id,
                        'period' => $periodStr,
                    ],
                    array_merge([
                        'status' => 'approved', // make it approved or paid for dummy
                        'payment_date' => Carbon::create($year, $month)->endOfMonth()->format('Y-m-d'),
                    ], $calc)
                );
            }
        }
    }
}
