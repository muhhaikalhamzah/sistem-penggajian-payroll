<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyAttendanceController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        
        if (!$employee) {
            return back()->withError('Akun Anda tidak tertaut dengan data Karyawan.');
        }

        $attendances = $employee->attendanceRecords()->latest('record_date')->get();

        return view('my-attendance.index', [
            'title' => 'Absensi Saya',
            'attendances' => $attendances,
        ]);
    }
}
