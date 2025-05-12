<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Leave;
use App\Models\Gaji; // <--- Tambahan import
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function employeeDashboard()
    {
        $today = Carbon::today();
        $presence = Presence::where('employee_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        $leaves = Leave::where('employee_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $isAbsentTime = Carbon::now()->format('H:i') > '10:00' && !$presence;

        return view('employee.dashboard.employee', compact('presence', 'leaves', 'isAbsentTime'));
    }

    public function adminDashboard()
    {
        $today = Carbon::today();
        // Ensure libur presences for holidays/weekends
        \App\Models\Presence::ensureLiburPresences($today);
        $now = Carbon::now();
        $lateTime = Carbon::createFromTimeString(env('ATTENDANCE_LATE_BEFORE', '10:00'));
        $isPastLateThreshold = $now->gt($lateTime);

        // Reset any 'absent' status to 'not_checked_in' if before threshold
        if (!$isPastLateThreshold) {
            Presence::whereDate('date', $today)
                ->where('status', 'absent')
                ->update(['status' => 'not_checked_in']);
        }

        // Get total employees
        $totalEmployees = Employee::count();

        // Get employees on leave today
        $onLeaveEmployees = Leave::where('status', 'approved')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->pluck('employee_id')
            ->toArray();
        $todayOnLeave = count($onLeaveEmployees);

        // Get today's presence statistics, excluding those on leave
        $todayPresent = Presence::whereDate('date', $today)
            ->whereNotIn('employee_id', $onLeaveEmployees)
            ->where('status', 'present')
            ->count();

        $todayLate = Presence::whereDate('date', $today)
            ->whereNotIn('employee_id', $onLeaveEmployees)
            ->where('status', 'late')
            ->count();

        // Calculate absent count only if past threshold
        $todayAbsent = 0;
        if ($isPastLateThreshold) {
            $todayAbsent = Presence::whereDate('date', $today)
                ->whereNotIn('employee_id', $onLeaveEmployees)
                ->where('status', 'absent')
                ->count();
        }

        // Get pending leaves
        $pendingLeaves = Leave::where('status', 'pending')->count();

        // Get recent leave requests
        $recentLeaves = Leave::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get all employees sorted by name
        $employees = Employee::orderBy('name')->get();
        
        // Get existing presences for today
        $presences = Presence::with('employee')
            ->whereDate('date', $today)
            ->orderBy('updated_at', 'desc')  // Sort by most recent update
            ->get();
            
        // Create attendance records array
        $attendances = collect();
        
        // Track processed employee IDs
        $processedEmployees = [];
        
        // First, add existing presences (they have timestamps)
        foreach ($presences as $presence) {
            if (!in_array($presence->employee_id, $processedEmployees)) {
                // Only update to absent if past threshold
                if ($isPastLateThreshold && $presence->status === 'not_checked_in') {
                    $presence->status = 'absent';
                    $presence->save();
                }
                $attendances->push($presence);
                $processedEmployees[] = $presence->employee_id;
            }
        }

        // Then handle employees on leave
        foreach ($onLeaveEmployees as $employeeId) {
            if (!in_array($employeeId, $processedEmployees)) {
                $employee = Employee::find($employeeId);
                if ($employee) {
                    // Get or create presence record
                    $attendance = Presence::firstOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'date' => $today
                        ],
                        [
                            'status' => 'on_leave',
                            'check_in' => null,
                            'check_out' => null
                        ]
                    );
                    $attendance->employee = $employee;
                    $attendance->is_on_leave = true;
                    $attendances->push($attendance);
                    $processedEmployees[] = $employeeId;
                }
            }
        }
        
        // Handle remaining employees
        foreach ($employees as $employee) {
            if (!in_array($employee->employee_id, $processedEmployees)) {
                // Get or create presence record with not_checked_in status
                $attendance = Presence::firstOrCreate(
                    [
                        'employee_id' => $employee->employee_id,
                        'date' => $today
                    ],
                    [
                        'status' => 'not_checked_in',
                        'check_in' => null,
                        'check_out' => null
                    ]
                );
                
                // Only update to absent if past threshold
                if ($isPastLateThreshold && $attendance->status === 'not_checked_in') {
                    $attendance->status = 'absent';
                    $attendance->save();
                }
                
                $attendance->employee = $employee;
                $attendances->push($attendance);
            }
        }

        // Sort by updated_at timestamp (most recent first) and take 5 records
        $recentAttendances = $attendances->sortByDesc(function ($attendance) {
            return $attendance->updated_at ?? Carbon::now();
        })->values()->take(5);

        // ==== Tambahan untuk recentPayrolls ====
        $recentPayrolls = Gaji::with('employee')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        // ========================================

        return view('admin.dashboard.admin', compact(
            'totalEmployees',
            'todayPresent',
            'todayLate',
            'todayAbsent',
            'todayOnLeave',
            'pendingLeaves',
            'recentLeaves',
            'recentAttendances',
            'recentPayrolls' // <-- tambahan disini
        ));
    }
}
