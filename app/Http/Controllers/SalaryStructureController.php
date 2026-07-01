<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalaryStructureController extends Controller
{
    public function index()
    {
        return view('salary-structure.index', [
            'title' => 'Struktur Gaji Pokok',
            'salaryStructures' => SalaryStructure::with('employee')->orderBy('effective_date', 'desc')->get(),
        ]);
    }

    public function create()
    {
        return view('salary-structure.create', [
            'title' => 'Tambah Struktur Gaji',
            'employees' => Employee::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'effective_date' => [
                'required',
                'date',
                Rule::unique('salary_structures')->where(function ($query) use ($request) {
                    return $query->where('employee_id', $request->employee_id)
                                 ->where('effective_date', $request->effective_date);
                }),
            ],
        ], [
            'employee_id.required' => 'Karyawan wajib dipilih',
            'basic_salary.required' => 'Gaji pokok wajib diisi',
            'effective_date.required' => 'Tanggal efektif wajib diisi',
            'effective_date.unique' => 'Karyawan ini sudah memiliki struktur gaji pada tanggal tersebut',
        ]);

        DB::beginTransaction();
        try {
            SalaryStructure::create($validate);
            DB::commit();
            return to_route('salary-structure.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('salary-structure.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(SalaryStructure $salaryStructure)
    {
        return view('salary-structure.show', [
            'title' => 'Detail Struktur Gaji',
            'salaryStructure' => $salaryStructure,
        ]);
    }

    public function edit(SalaryStructure $salaryStructure)
    {
        return view('salary-structure.edit', [
            'title' => 'Edit Struktur Gaji',
            'salaryStructure' => $salaryStructure,
            'employees' => Employee::all(),
        ]);
    }

    public function update(Request $request, SalaryStructure $salaryStructure)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'effective_date' => [
                'required',
                'date',
                Rule::unique('salary_structures')->ignore($salaryStructure->id)->where(function ($query) use ($request) {
                    return $query->where('employee_id', $request->employee_id)
                                 ->where('effective_date', $request->effective_date);
                }),
            ],
        ], [
            'employee_id.required' => 'Karyawan wajib dipilih',
            'basic_salary.required' => 'Gaji pokok wajib diisi',
            'effective_date.required' => 'Tanggal efektif wajib diisi',
            'effective_date.unique' => 'Karyawan ini sudah memiliki struktur gaji pada tanggal tersebut',
        ]);

        DB::beginTransaction();
        try {
            $salaryStructure->update($validate);
            DB::commit();
            return to_route('salary-structure.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('salary-structure.edit', $salaryStructure)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(SalaryStructure $salaryStructure)
    {
        DB::beginTransaction();
        try {
            $salaryStructure->delete();
            DB::commit();
            return to_route('salary-structure.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('salary-structure.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
