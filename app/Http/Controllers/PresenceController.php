<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presence;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    // Change these values to your desired times
    const PRESENT_BEFORE = '09:00'; // Present if check-in before this time
    const LATE_BEFORE = '10:00';    // Late if check-in before this time, otherwise absent
    
    protected function getPresentBeforeTime()
    {
        return env('ATTENDANCE_PRESENT_BEFORE', self::PRESENT_BEFORE);
    }

    protected function getLateBeforeTime()
    {
        return env('ATTENDANCE_LATE_BEFORE', self::LATE_BEFORE);
    }

    protected function ensureTodayRecord()
    {
        $employee = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();

        // Get or create today's record
        $presence = Presence::where('employee_id', $employee->employee_id)
            ->whereDate('date', $today)
            ->first();

        if (!$presence) {
            // Check if employee is on leave
            $onLeave = Leave::where('employee_id', $employee->employee_id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->exists();

            // Create presence record with proper status
            $status = 'not_checked_in';
            if ($onLeave) {
                $status = 'on_leave';
            } else if ($now->format('H:i') > $this->getLateBeforeTime()) {
                $status = 'absent';
            }

            // Create presence record
            $presence = Presence::create([
                'employee_id' => $employee->employee_id,
                'date' => $today,
                'status' => $status,
                'check_in' => null,
                'check_out' => null
            ]);
        }

        return $presence;
    }
    
    protected function isAttendanceFormOpen()
    {
        $now = Carbon::now();
        $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
        $formOpenTime = $presentBeforeTime->copy()->subMinutes(30);
        
        return $now->gte($formOpenTime);
    }

    public function index()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Get or create today's record
        $presence = $this->ensureTodayRecord();

        // Update status to absent if past late threshold and not checked in
        if (!$presence->check_in && 
            $presence->status === 'not_checked_in' && 
            $now->format('H:i') > $this->getLateBeforeTime()) {
            $presence->update(['status' => 'absent']);
            $presence->refresh();
        }

        // Calculate form open time
        $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
        $formOpenTime = $presentBeforeTime->copy()->subMinutes(30)->format('H:i');

        return view('presence.index', [
            'status' => $presence->status,
            'presence' => $presence,
            'now' => $now->format('H:i'),
            'presentBefore' => $this->getPresentBeforeTime(),
            'lateBefore' => $this->getLateBeforeTime(),
            'formOpenTime' => $formOpenTime
        ]);
    }

    public function checkIn()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $now = Carbon::now();
        $today = Carbon::today();

        // Get or create today's record
        $presence = $this->ensureTodayRecord();

        // Check if on leave
        if ($presence->status === 'on_leave') {
            return back()->with('error', 'You cannot check in while on leave.');
        }

        // Check if already checked in
        if ($presence->check_in) {
            return back()->with('error', 'You have already checked in today.');
        }

        // Check if past late threshold
        if ($now->format('H:i') > $this->getLateBeforeTime()) {
            return back()->with('error', 'Check-in is no longer available. You are marked as absent for today.');
        }

        // Check if attendance form is not yet open
        if (!$this->isAttendanceFormOpen()) {
            $presentBeforeTime = Carbon::createFromTimeString($this->getPresentBeforeTime());
            $formOpenTime = $presentBeforeTime->copy()->subMinutes(30)->format('H:i');
            return back()->with('error', "Attendance form is not open yet. You can check in from {$formOpenTime}.");
        }

        // Determine status based on time
        $status = $now->format('H:i') > $this->getPresentBeforeTime() ? 'late' : 'present';

        // Update presence record
        $presence->update([
            'check_in' => $now,
            'status' => $status
        ]);

        return back()->with('success', 'Successfully checked in.');
    }

    public function checkOut()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $now = Carbon::now();
        
        // Get today's record
        $presence = $this->ensureTodayRecord();

        // Check if on leave
        if ($presence->status === 'on_leave') {
            return back()->with('error', 'You cannot check out while on leave.');
        }

        // Check if not checked in
        if (!$presence->check_in) {
            return back()->with('error', 'You need to check in first.');
        }

        // Check if already checked out
        if ($presence->check_out) {
            return back()->with('error', 'You have already checked out today.');
        }

        // Update presence record
        $presence->update([
            'check_out' => $now
        ]);

        return back()->with('success', 'Successfully checked out.');
    }

    public function history()
    {
        $employee = Auth::user();
        if (!$employee || !$employee->employee_id) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }
        
        // First update any old records that should be absent
        $oldRecords = Presence::where('employee_id', $employee->employee_id)
            ->where('status', 'not_checked_in')
            ->where('date', '<', now()->format('Y-m-d'))
            ->get();

        foreach ($oldRecords as $record) {
            $record->update(['status' => 'absent']);
        }

        // Then get paginated records
        $presences = Presence::where('employee_id', $employee->employee_id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('presence.history', compact('presences'));
    }
}
