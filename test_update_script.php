<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\Allowance;
use App\Models\Deduction;
use App\Models\Setting;
use App\Models\AttendanceRecord;
use App\Models\LeaveRequest;

$admin = User::where('role', 'Superadmin')->first();
if (!$admin) {
    echo "Tidak ada Superadmin.\n";
    exit;
}

function runControllerUpdateTest($featureName, $modelClass, $urlPrefix, $updateData) {
    global $app, $kernel, $admin;

    $model = $modelClass::latest()->first();
    if (!$model) {
        return "$featureName|Superadmin|Update|-|SKIP|Tidak ada data untuk di-edit.";
    }

    $url = $urlPrefix . '/' . $model->id;
    
    $req = Request::create($url, 'PUT', $updateData);
    $req->setUserResolver(function () use ($admin) {
        return $admin;
    });

    $session = $app->make('session');
    $req->setLaravelSession($session->driver());
    $app->instance('middleware.disable', true);

    try {
        $response = $kernel->handle($req);
        $status = $response->getStatusCode();
        
        if ($status == 302 || $status == 200) {
            if ($session->driver()->has('errors')) {
                $errors = $session->driver()->get('errors')->messages();
                $errStr = substr(json_encode($errors), 0, 100);
                return "$featureName|Superadmin|Update|".$model->id."|302|Validation Error: $errStr";
            }
            return "$featureName|Superadmin|Update|".$model->id."|$status OK|Data ter-update.";
        }
        return "$featureName|Superadmin|Update|".$model->id."|$status|Gagal.";
    } catch (\Throwable $e) {
        return "$featureName|Superadmin|Update|".$model->id."|500 Error|Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
    }
}

$results = [];

$results[] = runControllerUpdateTest('User', User::class, '/user', [
    'name' => 'User Updated ' . time(),
    'email' => 'updated_' . time() . '@example.com',
    'role' => 'Admin'
]);

$results[] = runControllerUpdateTest('Departemen', Department::class, '/department', [
    'name' => 'Dept Updated ' . time(),
    'description' => 'Updated Desc'
]);

$results[] = runControllerUpdateTest('Jabatan', Position::class, '/position', [
    'title' => 'Pos Updated ' . time(),
    'department_id' => Department::latest()->first()->id ?? 1,
    'min_salary' => 6000000,
    'max_salary' => 12000000,
]);

$pos = Position::latest()->first();
$dept = Department::latest()->first();
$empData = [];
$emp = Employee::latest()->first();
if ($emp) {
    $empData = [
        'employee_number' => $emp->employee_number,
        'first_name' => 'First Upd',
        'last_name' => 'Last Upd',
        'ptkp_status' => 'TK/0',
        'join_date' => '2020-01-01',
        'department_id' => $dept->id ?? 1,
        'position_id' => $pos->id ?? 1,
    ];
}
$results[] = runControllerUpdateTest('Pegawai', Employee::class, '/employee', $empData);

$emp = Employee::latest()->first();

$results[] = runControllerUpdateTest('Struktur Gaji', SalaryStructure::class, '/salary-structure', [
    'employee_id' => $emp->id ?? 1,
    'basic_salary' => 8500000,
    'effective_date' => '2023-02-01'
]);

$results[] = runControllerUpdateTest('Tunjangan', Allowance::class, '/allowance', [
    'employee_id' => $emp->id ?? 1,
    'name' => 'Tunjangan Upd',
    'type' => 'Variabel',
    'amount' => 600000
]);

$results[] = runControllerUpdateTest('Potongan', Deduction::class, '/deduction', [
    'employee_id' => $emp->id ?? 1,
    'name' => 'Potongan Upd',
    'type' => 'Variabel',
    'amount' => 150000
]);

$results[] = runControllerUpdateTest('Absensi', AttendanceRecord::class, '/attendance', [
    'employee_id' => $emp->id ?? 1,
    'record_date' => '2023-11-01',
    'status' => 'Hadir',
    'check_in' => '08:00',
    'check_out' => '17:30'
]);

// $results[] = runControllerUpdateTest('Setting', Setting::class, '/setting/1/update', [ // wait, setting uses custom update? 

echo implode("\n", $results);
