<?php
namespace Tests;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\SalaryStructure;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;
use App\Models\Payslip;
use App\Models\TaxRecord;
use Illuminate\Support\Facades\Hash;

class E2EPayslipTest {
    public static function run() {
        echo "Starting E2E Integration Test...\n";

        // 1. Create Department & Position
        $dept = Department::firstOrCreate(['name' => 'IT Department']);
        $pos = Position::firstOrCreate(['title' => 'Backend Developer'], ['min_salary' => 5000000, 'max_salary' => 15000000]);

        // 2. Create User & Employee
        $user = User::firstOrCreate(
            ['email' => 'test_e2e@example.com'],
            [
                'name' => 'Test Employee',
                'password' => Hash::make('password'),
                'role' => 'Employee'
            ]
        );

        $employee = Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_number' => 'EMP-E2E-001',
                'first_name' => 'Test',
                'last_name' => 'Employee',
                'department_id' => $dept->id,
                'position_id' => $pos->id,
                'ptkp_status' => 'K/1', // Kawin 1 anak
                'join_date' => '2024-01-01'
            ]
        );

        echo "Created Employee ID: {$employee->id}\n";

        // 3. Setup Salary Structure
        SalaryStructure::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'basic_salary' => 10000000, // 10 juta
                'effective_date' => '2024-01-01'
            ]
        );

        // 4. Add Allowances & Deductions
        Allowance::updateOrCreate(
            ['employee_id' => $employee->id, 'name' => 'Transport'],
            ['amount' => 1500000, 'type' => 'Tetap']
        );
        Deduction::updateOrCreate(
            ['employee_id' => $employee->id, 'name' => 'BPJS Kesehatan'],
            ['amount' => 100000] // 100rb master deduction
        );

        // 5. Input Attendance (Target period: 10-2024)
        $period = '10-2024';
        $month = '10';
        $year = '2024';
        
        // Clean up old attendance for this test
        AttendanceRecord::where('employee_id', $employee->id)->whereMonth('record_date', $month)->delete();
        LeaveRequest::where('employee_id', $employee->id)->delete();
        Payslip::where('employee_id', $employee->id)->where('period', $period)->delete();
        TaxRecord::where('employee_id', $employee->id)->where('period', $period)->delete();

        // Add regular attendance
        AttendanceRecord::create(['employee_id' => $employee->id, 'record_date' => "$year-$month-01", 'status' => 'Hadir']);
        AttendanceRecord::create(['employee_id' => $employee->id, 'record_date' => "$year-$month-02", 'status' => 'Hadir']);
        AttendanceRecord::create(['employee_id' => $employee->id, 'record_date' => "$year-$month-03", 'status' => 'Alpa']); // Alpha 1
        AttendanceRecord::create(['employee_id' => $employee->id, 'record_date' => "$year-$month-04", 'status' => 'Alpa']); // Alpha 2
        AttendanceRecord::create(['employee_id' => $employee->id, 'record_date' => "$year-$month-05", 'status' => 'Alpa']); // Alpha 3

        echo "Inputted Attendance Records.\n";

        // 6. Request Leave & Approve it (Should override Alpha 3)
        $leaveReq = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type' => 'Tahunan',
            'start_date' => "$year-$month-05", // Covers Alpha 3
            'end_date' => "$year-$month-05",
            'reason' => 'Urusan Keluarga',
            'status' => 'Menunggu'
        ]);

        // Simulate HR Approving Leave
        app(\App\Http\Controllers\LeaveRequestController::class)->update(
            new \Illuminate\Http\Request(['status' => 'Disetujui']), 
            $leaveReq
        );
        
        echo "Approved Leave. Total Alpa should now be 2.\n";
        
        $finalAlpa = AttendanceRecord::where('employee_id', $employee->id)
                    ->whereMonth('record_date', $month)
                    ->where('status', 'Alpa')
                    ->count();
        echo "Final Alpa Count in DB: $finalAlpa\n";

        // 7. Generate Payslip
        app(\App\Http\Controllers\PayslipController::class)->store(
            new \Illuminate\Http\Request(['period' => $period])
        );

        echo "Generated Payslip.\n";

        // 8. Verify the math
        $payslip = Payslip::where('employee_id', $employee->id)->where('period', $period)->first();
        $tax = TaxRecord::where('employee_id', $employee->id)->where('period', $period)->first();

        echo "\n=== PAYSLIP RESULT ===\n";
        echo "Gross Salary: Rp " . number_format($payslip->gross_salary, 0) . "\n";
        echo "Master Deductions: Rp 100,000\n";
        
        $expectedAlphaCost = (10000000 / 22) * 2;
        echo "Alpha Deductions (Expected 2 days): Rp " . number_format($expectedAlphaCost, 0) . "\n";
        
        echo "PPh 21 (Monthly): Rp " . number_format($tax->pph21_amount, 0) . "\n";
        echo "Total Deductions: Rp " . number_format($payslip->total_deductions, 0) . "\n";
        echo "Net Salary: Rp " . number_format($payslip->net_salary, 0) . "\n";
        echo "======================\n";
    }
}
