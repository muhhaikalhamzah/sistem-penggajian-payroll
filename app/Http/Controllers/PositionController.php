<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        return view('position.index', [
            'title' => 'Jabatan',
            'positions' => Position::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('position.create', [
            'title' => 'Tambah Jabatan',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|min:0|gte:min_salary',
        ], [
            'title.required' => 'Nama jabatan wajib diisi',
            'min_salary.required' => 'Gaji minimum wajib diisi',
            'max_salary.required' => 'Gaji maksimum wajib diisi',
            'max_salary.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum',
        ]);

        DB::beginTransaction();
        try {
            Position::create($validate);
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Position $position)
    {
        return view('position.show', [
            'title' => 'Detail Jabatan',
            'position' => $position,
        ]);
    }

    public function edit(Position $position)
    {
        return view('position.edit', [
            'title' => 'Edit Jabatan',
            'position' => $position,
        ]);
    }

    public function update(Request $request, Position $position)
    {
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|min:0|gte:min_salary',
        ], [
            'title.required' => 'Nama jabatan wajib diisi',
            'min_salary.required' => 'Gaji minimum wajib diisi',
            'max_salary.required' => 'Gaji maksimum wajib diisi',
            'max_salary.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum',
        ]);

        DB::beginTransaction();
        try {
            $position->update($validate);
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.edit', $position)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Position $position)
    {
        DB::beginTransaction();
        try {
            $position->delete();
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
