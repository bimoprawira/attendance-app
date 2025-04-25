<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(Leave $leave)
    {
        try {
            if ($leave->status !== 'pending') {
                return redirect()->back()->with('error', 'Permintaan cuti telah diproses.');
            }

            // Load the employee relationship if not already loaded
            if (!$leave->relationLoaded('employee')) {
                $leave->load('employee');
            }

            // Calculate duration
            $duration = $leave->duration; // Using the accessor from the Leave model

            // Update the employee's leave quota
            $quotaField = 'used_' . strtolower($leave->type) . '_leave';
            $leave->employee->increment($quotaField, $duration);

            // Update leave status
            $leave->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::guard('admin')->id()
            ]);

            return redirect()->back()->with('success', 'Permintaan cuti berhasil disetujui.');
        } catch (\Exception $e) {
            \Log::error('Leave approval error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses permintaan cuti.');
        }
    }

    public function reject(Leave $leave)
    {
        try {
            if ($leave->status !== 'pending') {
                return redirect()->back()->with('error', 'Permintaan cuti sudah diproses.');
            }

            $leave->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => Auth::guard('admin')->id()
            ]);

            return redirect()->back()->with('success', 'Permintaan cuti berhasil ditolak.');
        } catch (\Exception $e) {
            \Log::error('Leave rejection error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses permintaan cuti.');
        }
    }
} 