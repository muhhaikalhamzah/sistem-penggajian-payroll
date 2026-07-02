<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = LeaveRequest::with('employee')->latest()->get();
        return view('leave-request.index', [
            'title' => 'Pengajuan Cuti',
            'leaves' => $leaves
        ]);
    }

    public function show(LeaveRequest $leaveRequest)
    {
        return view('leave-request.show', [
            'title' => 'Detail Cuti',
            'leave' => $leaveRequest
        ]);
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        $leaveRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Status cuti berhasil diperbarui.');
    }
}
