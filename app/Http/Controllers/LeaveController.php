<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('employee_id', Auth::user()->employee_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:annual,sick,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|min:10',
        ]);

        $employee = Auth::user();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $endDate->diffInDays($startDate) + 1;

        // Check available quota
        $quotaField = $request->type . '_leave_quota';
        $usedField = 'used_' . $request->type . '_leave';
        $availableQuota = $employee->$quotaField - $employee->$usedField;

        if ($days > $availableQuota) {
            return back()->with('error', "Insufficient {$request->type} leave quota. You only have {$availableQuota} days available.");
        }

        // Create leave request
        $leave = Leave::create([
            'employee_id' => $employee->employee_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show($id)
    {
        $leave = Leave::where('employee_id', Auth::user()->employee_id)
            ->findOrFail($id);

        return response()->json([
            'type' => ucfirst($leave->type),
            'start_date' => $leave->start_date->format('M d, Y'),
            'end_date' => $leave->end_date->format('M d, Y'),
            'status' => ucfirst($leave->status),
            'reason' => $leave->reason,
        ]);
    }

    // Admin methods
    public function adminIndex()
    {
        $leaves = Leave::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.leaves.index', compact('leaves'));
    }

    public function approve(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $employee = $leave->employee;
        $days = $leave->end_date->diffInDays($leave->start_date) + 1;
        $usedField = 'used_' . $leave->type . '_leave';
        
        // Update used quota
        $employee->increment($usedField, $days);

        $leave->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'This leave request has already been processed.');
        }

        $leave->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Leave request rejected successfully.');
    }
}
