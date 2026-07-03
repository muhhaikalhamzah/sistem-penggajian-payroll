<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeductionController extends Controller
{
    public function index()
    {
        return view('deduction.index', [
            'title' => 'Potongan Karyawan',
            'deductions' => Deduction::with('employee')->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('deduction.create', [
            'title' => 'Tambah Potongan',
            'employees' => Employee::all(),
        ]);
    }

    public function store(\App\Http\Requests\StoreDeductionRequest $request)
    {
        $validate = $request->validated();

        DB::beginTransaction();
        try {
            Deduction::create($validate);
            DB::commit();
            return to_route('deduction.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Deduction $deduction)
    {
        return view('deduction.show', [
            'title' => 'Detail Potongan',
            'deduction' => $deduction,
        ]);
    }

    public function edit(Deduction $deduction)
    {
        return view('deduction.edit', [
            'title' => 'Edit Potongan',
            'deduction' => $deduction,
            'employees' => Employee::all(),
        ]);
    }

    public function update(\App\Http\Requests\UpdateDeductionRequest $request, Deduction $deduction)
    {
        $validate = $request->validated();

        DB::beginTransaction();
        try {
            $deduction->update($validate);
            DB::commit();
            return to_route('deduction.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.edit', $deduction)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Deduction $deduction)
    {
        DB::beginTransaction();
        try {
            $deduction->delete();
            DB::commit();
            return to_route('deduction.index')->withSuccess('Potongan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
