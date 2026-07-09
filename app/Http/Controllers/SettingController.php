<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('setting.index', [
            'title' => 'Setting',
            'setting' => Setting::first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {

        $validate = $request->validate([
            'app_name' => 'required',
            'copyright' => 'required',
            'login_title' => 'required',
            'keywords' => 'nullable',
            'description' => 'nullable',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512'
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi',
            'copyright.required' => 'Copyright wajib diisi',
            'login_title.required' => 'Judul login wajib diisi',
            'logo.image' => 'File logo harus berupa gambar',
            'logo.mimes' => 'Format logo harus png, jpg, jpeg, atau svg',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 512 KB',
        ]);

        DB::beginTransaction();

        try {

            if ($request->logo_base64) {
                // Decode base64 image
                $image_parts = explode(";base64,", $request->logo_base64);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $file_name = 'img/' . uniqid() . '.png';
                    
                    Storage::disk('public')->put($file_name, $image_base64);
                    $validate['logo'] = $file_name;

                    if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                        Storage::disk('public')->delete($setting->logo);
                    }
                }
            }

            $setting->update($validate);

            DB::commit();
            return to_route('setting.index')->withSuccess('Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('setting.index')->withError('Gagal menyimpan data: ' . $e->getMessage());
        }
    }
}
