<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Employee::class);
        
        $employees = Employee::with(['department', 'position', 'user'])->latest()->get();
        
        if (strtolower(auth()->user()->role) === 'hr') {
            $employees = $employees->filter(function($emp) {
                return !in_array(strtolower($emp->user?->role ?? ''), ['superadmin', 'admin', 'hr']);
            });
        }

        return view('employee.index', [
            'title' => 'Karyawan',
            'employees' => $employees,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Employee::class);
        
        return view('employee.create', [
            'title' => 'Tambah Karyawan',
            'departments' => Department::all(),
            'positions' => Position::all(),
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Employee::class);
        
        $validate = $request->validate([
            'employee_number' => 'required|string|unique:employees,employee_number',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'ptkp_status' => 'required|string|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3,K/I/0,K/I/1,K/I/2,K/I/3',
            'join_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'employee_number.required' => 'NIK wajib diisi',
            'employee_number.unique' => 'NIK sudah terdaftar',
            'first_name.required' => 'Nama depan wajib diisi',
            'last_name.required' => 'Nama belakang wajib diisi',
            'ptkp_status.required' => 'Status PTKP wajib diisi',
            'ptkp_status.in' => 'Format PTKP tidak valid (misal: TK/0, K/1)',
            'join_date.required' => 'Tanggal bergabung wajib diisi',
            'department_id.required' => 'Departemen wajib diisi',
            'position_id.required' => 'Jabatan wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            Employee::create($validate);
            DB::commit();
            return to_route('employee.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('employee.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        Gate::authorize('view', $employee);
        
        return view('employee.show', [
            'title' => 'Detail Karyawan',
            'employee' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        Gate::authorize('update', $employee);
        
        return view('employee.edit', [
            'title' => 'Edit Karyawan',
            'employee' => $employee,
            'departments' => Department::all(),
            'positions' => Position::all(),
            'users' => User::all(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        Gate::authorize('update', $employee);
        
        $validate = $request->validate([
            'employee_number' => 'required|string|unique:employees,employee_number,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'ptkp_status' => 'required|string|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3,K/I/0,K/I/1,K/I/2,K/I/3',
            'join_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => 'nullable|exists:users,id',
        ], [
            'employee_number.required' => 'NIK wajib diisi',
            'employee_number.unique' => 'NIK sudah terdaftar',
            'first_name.required' => 'Nama depan wajib diisi',
            'last_name.required' => 'Nama belakang wajib diisi',
            'ptkp_status.required' => 'Status PTKP wajib diisi',
            'ptkp_status.in' => 'Format PTKP tidak valid (misal: TK/0, K/1)',
            'join_date.required' => 'Tanggal bergabung wajib diisi',
            'department_id.required' => 'Departemen wajib diisi',
            'position_id.required' => 'Jabatan wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $employee->update($validate);
            DB::commit();
            return to_route('employee.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('employee.edit', $employee)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        Gate::authorize('delete', $employee);
        
        DB::beginTransaction();
        try {
            $employee->delete();
            DB::commit();
            return to_route('employee.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('employee.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
