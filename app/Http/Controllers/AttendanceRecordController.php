<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceRecordController extends Controller
{
    public function index()
    {
        return view('attendance.index', [
            'title' => 'Rekap Absensi',
            'attendances' => AttendanceRecord::with('employee')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('attendance.create', [
            'title' => 'Input Absensi Manual',
            'employees' => Employee::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'record_date' => 'required|date',
            'check_in' => ['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'check_out' => ['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'status' => 'required|in:Hadir,Alpa,Cuti',
        ]);

        $overtimeHours = 0;
        if (!empty($validate['check_in']) && !empty($validate['check_out'])) {
            $recordDate = $validate['record_date'];
            $checkOutDate = $request->input('check_out_date', $recordDate) ?: $recordDate;
            
            $checkInStr = substr($validate['check_in'], 0, 5);
            $checkOutStr = substr($validate['check_out'], 0, 5);
            
            $checkIn = Carbon::createFromFormat('Y-m-d H:i', $recordDate . ' ' . $checkInStr);
            $checkOut = Carbon::createFromFormat('Y-m-d H:i', $checkOutDate . ' ' . $checkOutStr);
            $limit = Carbon::createFromFormat('Y-m-d H:i', $recordDate . ' 17:00');

            if ($checkOut->greaterThan($limit)) {
                $minutes = $limit->diffInMinutes($checkOut);
                $overtimeHours = (int) ceil($minutes / 60);
            }
        }
        $validate['overtime_hours'] = $overtimeHours;
        
        if (!empty($validate['check_in'])) {
            $validate['check_in'] = substr($validate['check_in'], 0, 5);
        }
        if (!empty($validate['check_out'])) {
            $validate['check_out'] = substr($validate['check_out'], 0, 5);
        }

        DB::beginTransaction();
        try {
            AttendanceRecord::create($validate);
            DB::commit();
            return to_route('attendance.index')->withSuccess('Absensi berhasil ditambahkan');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return back()->withInput()->withError('Gagal menambahkan data: Karyawan sudah memiliki absensi pada tanggal tersebut.');
            }
            return back()->withInput()->withError('Gagal menambahkan data: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(AttendanceRecord $attendance)
    {
        return view('attendance.show', [
            'title' => 'Detail Absensi',
            'attendance' => $attendance,
        ]);
    }

    public function edit(AttendanceRecord $attendance)
    {
        return view('attendance.edit', [
            'title' => 'Edit Absensi',
            'attendance' => $attendance,
            'employees' => Employee::all(),
        ]);
    }

    public function update(Request $request, AttendanceRecord $attendance)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'record_date' => 'required|date',
            'check_in' => ['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'check_out' => ['nullable', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'status' => 'required|in:Hadir,Alpa,Cuti',
        ]);

        $overtimeHours = 0;
        if (!empty($validate['check_in']) && !empty($validate['check_out'])) {
            $recordDate = $validate['record_date'];
            $checkOutDate = $request->input('check_out_date', $recordDate) ?: $recordDate;
            
            $checkInStr = substr($validate['check_in'], 0, 5);
            $checkOutStr = substr($validate['check_out'], 0, 5);
            
            $checkIn = Carbon::createFromFormat('Y-m-d H:i', $recordDate . ' ' . $checkInStr);
            $checkOut = Carbon::createFromFormat('Y-m-d H:i', $checkOutDate . ' ' . $checkOutStr);
            $limit = Carbon::createFromFormat('Y-m-d H:i', $recordDate . ' 17:00');

            if ($checkOut->greaterThan($limit)) {
                $minutes = $limit->diffInMinutes($checkOut);
                $overtimeHours = (int) ceil($minutes / 60);
            }
        }
        $validate['overtime_hours'] = $overtimeHours;
        
        if (!empty($validate['check_in'])) {
            $validate['check_in'] = substr($validate['check_in'], 0, 5);
        }
        if (!empty($validate['check_out'])) {
            $validate['check_out'] = substr($validate['check_out'], 0, 5);
        }

        DB::beginTransaction();
        try {
            $attendance->update($validate);
            DB::commit();
            return to_route('attendance.index')->withSuccess('Absensi berhasil diubah');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                return back()->withInput()->withError('Gagal mengubah data: Karyawan sudah memiliki absensi pada tanggal tersebut.');
            }
            return back()->withInput()->withError('Gagal mengubah data: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(AttendanceRecord $attendance)
    {
        DB::beginTransaction();
        try {
            $attendance->delete();
            DB::commit();
            return to_route('attendance.index')->withSuccess('Absensi berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('attendance.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
