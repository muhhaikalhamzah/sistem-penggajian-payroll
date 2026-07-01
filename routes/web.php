<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    Route::resource('/user', UserController::class)->middleware('role:Superadmin,hr');
    
    Route::middleware('role:hr')->group(function () {
        Route::resource('/department', App\Http\Controllers\DepartmentController::class);
        Route::resource('/position', App\Http\Controllers\PositionController::class);
        Route::resource('/employee', App\Http\Controllers\EmployeeController::class);
    // Rekap Absensi & Cuti
    Route::resource('attendance', App\Http\Controllers\AttendanceRecordController::class);
    Route::resource('leave-requests', App\Http\Controllers\LeaveRequestController::class)->only(['index', 'update', 'show']);
});

// Employee Routes
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/my-attendance', [App\Http\Controllers\MyAttendanceController::class, 'index'])->name('my-attendance.index');
    Route::resource('my-leaves', App\Http\Controllers\MyLeaveController::class)->only(['index', 'create', 'store', 'show']);
});

    Route::middleware('role:finance')->group(function () {
        Route::resource('/salary-structure', App\Http\Controllers\SalaryStructureController::class);
        Route::resource('/allowance', App\Http\Controllers\AllowanceController::class);
        Route::resource('/deduction', App\Http\Controllers\DeductionController::class);
        Route::resource('tax-records', App\Http\Controllers\TaxRecordController::class)->only(['index', 'show']);
    });

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
});
