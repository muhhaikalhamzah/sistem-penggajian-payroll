<?php

namespace App\Http\Controllers;

use App\Models\TaxConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaxConfigController extends Controller
{
    public function index()
    {
        // Only superadmin and admin can access tax config
        if (!in_array(strtolower(auth()->user()->role), ['superadmin', 'admin'])) {
            abort(403);
        }

        return view('tax_config.index', [
            'title' => 'Konfigurasi Pajak & BPJS',
            'configs' => TaxConfig::all(),
        ]);
    }

    public function edit(TaxConfig $taxConfig)
    {
        if (!in_array(strtolower(auth()->user()->role), ['superadmin', 'admin'])) {
            abort(403);
        }

        return view('tax_config.edit', [
            'title' => 'Edit Konfigurasi',
            'config' => $taxConfig,
        ]);
    }

    public function update(Request $request, TaxConfig $taxConfig)
    {
        if (!in_array(strtolower(auth()->user()->role), ['superadmin', 'admin'])) {
            abort(403);
        }

        $validate = $request->validate([
            'rate_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
        ]);

        $taxConfig->update($validate);

        return to_route('tax-configs.index')->withSuccess('Konfigurasi berhasil diubah');
    }
}
