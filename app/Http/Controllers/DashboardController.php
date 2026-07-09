<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalUsers = \App\Models\User::count();
        $superadminCount = \App\Models\User::where('role', 'Superadmin')->count();
        $adminCount = \App\Models\User::where('role', 'Admin')->count();

        // Chart Data for Employee
        $payslipChartData = null;
        if (strtolower($user->role) === 'employee' && $user->employee) {
            $payslips = \App\Models\Payslip::where('employee_id', $user->employee->id)
                ->whereIn('status', ['approved', 'paid'])
                ->orderBy('created_at', 'asc')
                ->take(12)
                ->get();
                
            if ($payslips->count() > 0) {
                $payslipChartData = [
                    'labels' => [],
                    'data' => []
                ];
                foreach ($payslips as $payslip) {
                    $payslipChartData['labels'][] = $payslip->period;
                    $payslipChartData['data'][] = $payslip->net_salary;
                }
            }
        }

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'totalUsers' => $totalUsers,
            'superadminCount' => $superadminCount,
            'adminCount' => $adminCount,
            'payslipChartData' => $payslipChartData ? json_encode($payslipChartData) : null
        ]);
    }

    public function show()
    {
        return view('dashboard.show', [
            'title' => 'My Profile',
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('dashboard.edit', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $validate = $request->validate([
                'name' => 'required',
                'password' => 'nullable|min:8',
                'passwordconfirm' => 'nullable|same:password',
                'phone' => 'nullable|string|max:20',
                'email' => 'required|email|lowercase|unique:users,email,' . $user->id,
                'avatar' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512'
            ], [
                'name.required' => 'Nama wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'passwordconfirm.same' => 'Konfirmasi password tidak cocok',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'avatar.image' => 'File avatar harus berupa gambar',
                'avatar.mimes' => 'Format avatar harus png, jpg, jpeg, atau svg',
                'avatar.max' => 'Ukuran avatar tidak boleh lebih dari 512 KB',
            ]);

            if ($request->avatar_base64) {
                // Decode base64 image
                $image_parts = explode(";base64,", $request->avatar_base64);
                if (count($image_parts) == 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $file_name = 'img/' . uniqid() . '.png';
                    
                    Storage::disk('public')->put($file_name, $image_base64);
                    $validate['avatar'] = $file_name;

                    if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                }
            }

            if ($request->password) {
                $validate['password'] = bcrypt($request->password);
            } else {
                unset($validate['password']);
            }
            unset($validate['passwordconfirm']);
            $user->update($validate);

            DB::commit();
            return to_route('dashboard.show')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('dashboard.edit')->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }
}
