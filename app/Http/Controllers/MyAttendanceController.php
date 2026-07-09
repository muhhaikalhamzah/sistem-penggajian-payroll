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

        $attendances = $employee->attendanceRecords()->latest()->get();
        $todayAttendance = $employee->attendanceRecords()->where('record_date', now()->format('Y-m-d'))->first();

        return view('my-attendance.index', [
            'title' => 'Absensi Saya',
            'attendances' => $attendances,
            'todayAttendance' => $todayAttendance,
        ]);
    }

    public function checkIn(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) return back()->withError('Akun belum ditautkan ke data Karyawan.');

        $today = now()->format('Y-m-d');
        if ($employee->attendanceRecords()->where('record_date', $today)->exists()) {
            return back()->withError('Anda sudah melakukan absensi hari ini.');
        }

        try {
            \App\Models\AttendanceRecord::create([
                'employee_id' => $employee->id,
                'record_date' => $today,
                'check_in' => now()->format('H:i'),
                'status' => 'Hadir'
            ]);

            return back()->with('success', 'Berhasil Check-In pada ' . now()->format('H:i'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Check-In: ' . $e->getMessage());
        }
    }

    public function checkOut(Request $request)
    {
        $employee = Auth::user()->employee;
        if (!$employee) return back()->withError('Akun belum ditautkan ke data Karyawan.');

        $today = now()->format('Y-m-d');
        $attendance = $employee->attendanceRecords()->where('record_date', $today)->first();

        if (!$attendance) {
            return back()->withError('Anda belum Check-In hari ini.');
        }

        if ($attendance->check_out) {
            return back()->withError('Anda sudah Check-Out hari ini.');
        }

        $checkOutTime = now();
        $recordDate = $attendance->record_date->format('Y-m-d');
        $limit = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $recordDate . ' 17:00');
        
        $overtimeHours = 0;
        
        if ($checkOutTime->greaterThan($limit)) {
            $minutes = $limit->diffInMinutes($checkOutTime);
            $overtimeHours = (int) ceil($minutes / 60);
        }

        try {
            $attendance->update([
                'check_out' => $checkOutTime->format('H:i'),
                'overtime_hours' => $overtimeHours
            ]);

            return back()->with('success', 'Berhasil Check-Out pada ' . $checkOutTime->format('H:i'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Check-Out: ' . $e->getMessage());
        }
    }
}
