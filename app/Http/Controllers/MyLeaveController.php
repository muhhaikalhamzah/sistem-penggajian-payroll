<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class MyLeaveController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user->employee) {
            return redirect()->back()->with('error', 'Anda tidak terkait dengan data pegawai manapun.');
        }

        $leaves = LeaveRequest::where('employee_id', $user->employee->id)->latest()->get();

        return view('my-leave.index', [
            'title' => 'Cuti Saya',
            'leaves' => $leaves
        ]);
    }

    public function create()
    {
        return view('my-leave.create', [
            'title' => 'Ajukan Cuti',
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return redirect()->back()->with('error', 'Anda tidak terkait dengan data pegawai manapun.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_type' => 'required|in:Annual,Sick,Unpaid',
        ]);

        // Validation for overlapping leaves (only check Pending and Approved)
        $overlap = LeaveRequest::where('employee_id', $user->employee->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('start_date', '<=', $validated['start_date'])
                         ->where('end_date', '>=', $validated['end_date']);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'Tanggal yang Anda ajukan beririsan (overlap) dengan pengajuan cuti Anda yang lain (Pending/Approved).')->withInput();
        }

        LeaveRequest::create([
            'employee_id' => $user->employee->id,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'leave_type' => $validated['leave_type'],
            'status' => 'Pending',
        ]);

        return redirect()->route('my-leaves.index')->with('success', 'Pengajuan cuti berhasil dibuat.');
    }
    
    public function show(LeaveRequest $my_leaf)
    {
        $user = auth()->user();
        if ($my_leaf->employee_id !== $user->employee->id) {
            abort(403);
        }
        
        return view('my-leave.show', [
            'title' => 'Detail Cuti',
            'leave' => $my_leaf
        ]);
    }
}
