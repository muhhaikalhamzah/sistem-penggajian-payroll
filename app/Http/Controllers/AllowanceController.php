<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    public function index()
    {
        return view('allowance.index', [
            'title' => 'Tunjangan Karyawan',
            'allowances' => Allowance::with('employee')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('allowance.create', [
            'title' => 'Tambah Tunjangan',
            'employees' => Employee::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:Fixed,Variable',
        ]);

        DB::beginTransaction();
        try {
            Allowance::create($validate);
            DB::commit();
            return to_route('allowance.index')->withSuccess('Tunjangan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Allowance $allowance)
    {
        return view('allowance.show', [
            'title' => 'Detail Tunjangan',
            'allowance' => $allowance,
        ]);
    }

    public function edit(Allowance $allowance)
    {
        return view('allowance.edit', [
            'title' => 'Edit Tunjangan',
            'allowance' => $allowance,
            'employees' => Employee::all(),
        ]);
    }

    public function update(Request $request, Allowance $allowance)
    {
        $validate = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:Fixed,Variable',
        ]);

        DB::beginTransaction();
        try {
            $allowance->update($validate);
            DB::commit();
            return to_route('allowance.index')->withSuccess('Tunjangan berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.edit', $allowance)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Allowance $allowance)
    {
        DB::beginTransaction();
        try {
            $allowance->delete();
            DB::commit();
            return to_route('allowance.index')->withSuccess('Tunjangan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
