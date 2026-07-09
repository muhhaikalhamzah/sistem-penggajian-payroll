<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $payslip = \App\Models\Payslip::first();
    $user = \App\Models\User::where('role', 'Superadmin')->first();
    Auth::login($user);
    
    // Simulate what the controller does
    \Illuminate\Support\Facades\Gate::authorize('view', $payslip);
    echo "Gate passed for Superadmin.\n";
    
    // Simulate what show() does
    $payslip->load(['employee.user', 'employee.allowances', 'employee.deductions']);
    $taxRecord = \App\Models\TaxRecord::where('employee_id', $payslip->employee_id)
        ->where('period', $payslip->period)
        ->first();
    $parts = explode('-', $payslip->period);
    $alphaCount = 0;
    $overtimeHours = 0;
    if (count($parts) == 2) {
        $alphaCount = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
            ->whereMonth('record_date', $parts[0])
            ->whereYear('record_date', $parts[1])
            ->where('status', 'Alpa')
            ->count();
        $overtimeHours = \App\Models\AttendanceRecord::where('employee_id', $payslip->employee_id)
            ->whereMonth('record_date', $parts[0])
            ->whereYear('record_date', $parts[1])
            ->sum('overtime_hours');
    }
    $basicSalary = $payslip->employee?->salaryStructures->sortByDesc('effective_date')->first()?->basic_salary ?? 0;
    
    echo "Show logic passed.\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
