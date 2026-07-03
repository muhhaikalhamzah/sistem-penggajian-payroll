<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\Allowance;
use App\Models\Deduction;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Bypass CSRF Middleware
$app->instance(
    \App\Http\Middleware\VerifyCsrfToken::class, 
    new class {
        public function handle($request, $next) {
            return $next($request);
        }
    }
);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$results = [];

function runControllerStoreTest($name, $modelClass, $url, $method, $data) {
    global $kernel, $app;
    
    // Login as Superadmin
    $user = User::where('role', 'Superadmin')->first();
    auth()->login($user);
    
    $before = $modelClass::count();
    
    $token = 'test_token_123';
    session()->put('_token', $token);
    $data['_token'] = $token;
    
    // Create Request
    $request = Request::create($url, $method, $data);
    $request->setLaravelSession(session()->driver());
    
    $response = $kernel->handle($request);
    
    $after = $modelClass::count();
    $status = $response->getStatusCode() == 302 || $response->getStatusCode() == 200 ? 'OK' : 'Error ' . $response->getStatusCode();
    
    $notes = "";
    if ($after > $before) {
        $notes = "Data tersimpan.";
    } else {
        $notes = "Gagal tersimpan.";
        if (session()->has('errors')) {
            $errs = session('errors');
            $notes .= " Validation Error: " . json_encode(is_object($errs) ? (method_exists($errs, 'getMessages') ? $errs->getMessages() : (method_exists($errs, 'messages') ? $errs->messages() : $errs)) : $errs);
        }
    }
    
    return [
        'halaman' => $name,
        'role' => 'Superadmin',
        'aksi' => 'Store',
        'sebelum' => $before,
        'sesudah' => $after,
        'status' => $status,
        'catatan' => $notes
    ];
}

$results[] = runControllerStoreTest('User', User::class, '/user', 'POST', [
    'name' => 'Test Admin ' . time(),
    'email' => 'admin_test_' . time() . '@example.com',
    'role' => 'Admin',
    'password' => 'password123',
    'passwordconfirm' => 'password123'
]);

$results[] = runControllerStoreTest('Departemen', Department::class, '/department', 'POST', [
    'name' => 'Departemen Test ' . time(),
    'description' => 'Test Desc'
]);

// Need department ID for Position
$dept = Department::latest()->first();

$results[] = runControllerStoreTest('Jabatan', Position::class, '/position', 'POST', [
    'title' => 'Test Jabatan ' . time(),
    'department_id' => $dept->id ?? 1,
    'min_salary' => 5000000,
    'max_salary' => 10000000,
]);

$pos = Position::latest()->first();

$results[] = runControllerStoreTest('Pegawai', Employee::class, '/employee', 'POST', [
    'employee_number' => 'EMP' . time(),
    'first_name' => 'Test',
    'last_name' => 'Pegawai',
    'email' => 'pegawai_test_' . time() . '@example.com',
    'phone' => '08123456789',
    'address' => 'Test Address',
    'date_of_birth' => '1990-01-01',
    'gender' => 'L',
    'join_date' => '2020-01-01',
    'department_id' => $dept->id ?? 1,
    'position_id' => $pos->id ?? 1,
    'employment_status' => 'Tetap',
    'ptkp_status' => 'TK/0'
]);

$emp = Employee::latest()->first();

$results[] = runControllerStoreTest('Struktur Gaji', SalaryStructure::class, '/salary-structure', 'POST', [
    'employee_id' => $emp->id ?? 1,
    'basic_salary' => 7000000,
    'effective_date' => '2023-01-01'
]);

$results[] = runControllerStoreTest('Tunjangan', Allowance::class, '/allowance', 'POST', [
    'employee_id' => $emp->id ?? 1,
    'name' => 'Tunjangan Test',
    'type' => 'Tetap',
    'amount' => 500000
]);

$results[] = runControllerStoreTest('Potongan', Deduction::class, '/deduction', 'POST', [
    'employee_id' => $emp->id ?? 1,
    'name' => 'Potongan Test',
    'type' => 'Tetap',
    'amount' => 100000
]);

// Test pages access
$pages = [
    'Dashboard' => '/dashboard',
    'User' => '/user',
    'Departemen' => '/department',
    'Jabatan' => '/position',
    'Pegawai' => '/employee',
    'Struktur Gaji' => '/salary-structure',
    'Tunjangan' => '/allowance',
    'Potongan' => '/deduction',
    'Absensi Saya' => '/my-attendance',
    'Rekap Absensi' => '/attendance',
    'Cuti Saya' => '/my-leaves',
    'Pengajuan Cuti' => '/leave-requests',
    'Slip Gaji' => '/payslips',
    'Setting' => '/setting'
];

$roles = ['Superadmin', 'hr', 'finance', 'employee'];

foreach ($roles as $role) {
    $user = User::where('role', $role)->first();
    if (!$user) {
        $results[] = [
            'halaman' => '-',
            'role' => $role,
            'aksi' => 'Akses Index',
            'sebelum' => '-',
            'sesudah' => '-',
            'status' => 'Error',
            'catatan' => 'User dengan role ini tidak ditemukan'
        ];
        continue;
    }

    foreach ($pages as $name => $url) {
        $request = Request::create($url, 'GET');
        auth()->login($user);
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        
        $results[] = [
            'halaman' => $name,
            'role' => $role,
            'aksi' => 'Akses Index',
            'sebelum' => '-',
            'sesudah' => '-',
            'status' => $status == 200 ? '200 OK' : ($status == 403 ? '403 Forbidden' : ($status == 302 ? '302 Redirect' : $status)),
            'catatan' => $status == 500 ? 'Terjadi Error 500' : 'Berhasil'
        ];
    }
}

// Print table
echo "Halaman/Fitur|Role Diuji|Aksi|Jumlah Data Sebelum|Jumlah Data Sesudah|Status|Catatan/Error\n";
echo "---|---|---|---|---|---|---\n";
foreach ($results as $res) {
    echo "{$res['halaman']}|{$res['role']}|{$res['aksi']}|{$res['sebelum']}|{$res['sesudah']}|{$res['status']}|{$res['catatan']}\n";
}
