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

        if ($validated['status'] === 'Disetujui') {
            $startDate = \Carbon\Carbon::parse($leaveRequest->start_date);
            $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);
            
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                \App\Models\AttendanceRecord::updateOrCreate(
                    [
                        'employee_id' => $leaveRequest->employee_id,
                        'record_date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => 'Cuti',
                        'check_in' => null,
                        'check_out' => null,
                        'overtime_hours' => 0,
                    ]
                );
            }
        } else if ($validated['status'] === 'Ditolak') {
            // Optional: If rejected, we might want to revert the AttendanceRecord if it was previously approved.
            // But usually status goes from Pending -> Approved/Rejected.
            $startDate = \Carbon\Carbon::parse($leaveRequest->start_date);
            $endDate = \Carbon\Carbon::parse($leaveRequest->end_date);
            
            \App\Models\AttendanceRecord::where('employee_id', $leaveRequest->employee_id)
                ->whereBetween('record_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->where('status', 'Cuti')
                ->delete();
        }

        return back()->with('success', 'Status cuti berhasil diperbarui.');
    }
}
