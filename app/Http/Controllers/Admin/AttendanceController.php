<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get the requested date or use today
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $today = Carbon::today();
        $now = Carbon::now();
        $lateTime = Carbon::createFromTimeString(env('ATTENDANCE_LATE_BEFORE', '10:00'));
        $isPastLateThreshold = $now->gt($lateTime);
        
        // Ensure libur presences for holidays/weekends ONLY for today
        if ($date->isToday()) {
            \App\Models\Presence::ensureLiburPresences($date);
        }
        
        // Reset any 'absent' status to 'not_checked_in' if it's today and before threshold
        if ($date->isToday() && !$isPastLateThreshold) {
            Presence::whereDate('date', $date)
                ->where('status', 'absent')
                ->update(['status' => 'not_checked_in']);
        }
        
        // If future date, return empty result
        if ($date->isAfter($today)) {
            return view('admin.attendance.index', [
                'attendances' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(),
                    0,
                    10,
                    1,
                    ['path' => request()->url(), 'query' => request()->query()]
                )
            ]);
        }

        // Get all employees
        $employees = Employee::all();
        
        // Get existing presences for the date
        $presences = Presence::with('employee')
            ->whereDate('date', $date)
            ->orderBy('updated_at', 'desc')  // Sort by most recent update
            ->get();
            
        // Get employees on leave for the date
        $onLeaveEmployees = Leave::where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('employee_id')
            ->toArray();
            
        // Create attendance records array
        $attendances = collect();
        
        // Track processed employee IDs
        $processedEmployees = [];
        
        // First, add existing presences (they have timestamps)
        foreach ($presences as $presence) {
            if (!in_array($presence->employee_id, $processedEmployees)) {
                // If it's today and the status is not_checked_in, we might need to update it
                if ($date->isToday() && $presence->status === 'not_checked_in' && $isPastLateThreshold) {
                    $presence->status = 'absent';
                    $presence->save(); // Save the change to database
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
                    $attendance = new Presence();
                    $attendance->date = $date;
                    $attendance->status = 'on_leave';
                    $attendance->employee_id = $employeeId;
                    $attendance->employee = $employee;
                    $attendance->is_on_leave = true;
                    $attendances->push($attendance);
                    $processedEmployees[] = $employeeId;
                }
            }
        }
        
        // Handle remaining employees based on date
        if ($date->isToday()) {
            // Check if today is a holiday or weekend
            $isHoliday = false;
            $holidayName = null;
            if (class_exists('App\\Models\\Holiday')) {
                $holiday = \App\Models\Holiday::getHoliday($date);
                $isHoliday = $date->isWeekend() || $holiday;
                $holidayName = $holiday ? $holiday->name : null;
            } else {
                $isHoliday = $date->isWeekend();
            }
            foreach ($employees as $employee) {
                if ($isHoliday) {
                    // Only push if not already processed (avoid duplicate in collection)
                    if (!in_array($employee->employee_id, $processedEmployees)) {
                        $attendance = Presence::where('employee_id', $employee->employee_id)
                            ->whereDate('date', $date)
                            ->first();
                        if ($attendance) {
                            $attendance->employee = $employee;
                            $attendances->push($attendance);
                            $processedEmployees[] = $employee->employee_id;
                        }
                    }
                } else if (!in_array($employee->employee_id, $processedEmployees)) {
                    $attendance = Presence::create([
                        'employee_id' => $employee->employee_id,
                        'date' => $date,
                        'status' => 'not_checked_in',
                        'check_in' => null,
                        'check_out' => null
                    ]);
                    // If it's past late threshold, update to absent
                    if ($isPastLateThreshold) {
                        $attendance->status = 'absent';
                        $attendance->save();
                    }
                    $attendance->employee = $employee;
                    $attendances->push($attendance);
                    $processedEmployees[] = $employee->employee_id;
                }
            }
        } else if ($date->isBefore($today)) {
            // For past dates, only show existing records and actual absences (do not create or show 'libur' unless already exists)
            $existingRecords = $attendances->pluck('employee_id')->toArray();
            $employeesAtDate = $employees->filter(function($employee) use ($date) {
                return Carbon::parse($employee->date_joined)->lte($date);
            });
            foreach ($employeesAtDate as $employee) {
                if (!in_array($employee->employee_id, $existingRecords)) {
                    $attendance = new Presence();
                    $attendance->date = $date;
                    $attendance->status = 'absent';
                    $attendance->employee_id = $employee->employee_id;
                    $attendance->employee = $employee;
                    $attendances->push($attendance);
                }
            }
        }
        
        // Apply status filter if provided
        if ($request->filled('status')) {
            $attendances = $attendances->filter(function ($attendance) use ($request) {
                if ($request->status === 'on_leave') {
                    return ($attendance->status === 'on_leave') || (isset($attendance->is_on_leave) && $attendance->is_on_leave);
                }
                if ($request->status === 'not_checked_in') {
                    return $attendance->status === 'not_checked_in';
                }
                return $attendance->status === $request->status;
            });
        }

        // Apply search filter for employee name or email
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $attendances = $attendances->filter(function ($attendance) use ($search) {
                $name = strtolower($attendance->employee->name ?? '');
                $email = strtolower($attendance->employee->email ?? '');
                return strpos($name, $search) !== false || strpos($email, $search) !== false;
            });
        }

        // Sort by updated_at timestamp (most recent first)
        $attendances = $attendances->sortByDesc(function ($attendance) {
            return $attendance->updated_at ?? Carbon::now();
        })->values();
        
        // Convert to paginator
        $page = $request->get('page', 1);
        $perPage = 10;
        $items = $attendances->forPage($page, $perPage);
        $attendances = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $attendances->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.attendance.index', compact('attendances'));
    }

    public function ajaxTable(Request $request)
    {
        // Duplicate the logic from index, but only return the table partial
        $date = $request->filled('date') ? Carbon::parse($request->date) : Carbon::today();
        $today = Carbon::today();
        $now = Carbon::now();
        $lateTime = Carbon::createFromTimeString(env('ATTENDANCE_LATE_BEFORE', '10:00'));
        $isPastLateThreshold = $now->gt($lateTime);
        // Ensure libur presences for holidays/weekends ONLY for today
        if ($date->isToday()) {
            \App\Models\Presence::ensureLiburPresences($date);
        }
        if ($date->isToday() && !$isPastLateThreshold) {
            Presence::whereDate('date', $date)
                ->where('status', 'absent')
                ->update(['status' => 'not_checked_in']);
        }
        if ($date->isAfter($today)) {
            $attendances = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 10, 1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return view('admin.attendance._table', compact('attendances'))->render();
        }
        $employees = Employee::all();
        $presences = Presence::with('employee')
            ->whereDate('date', $date)
            ->orderBy('updated_at', 'desc')
            ->get();
        $onLeaveEmployees = Leave::where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('employee_id')
            ->toArray();
        $attendances = collect();
        $processedEmployees = [];
        foreach ($presences as $presence) {
            if (!in_array($presence->employee_id, $processedEmployees)) {
                if ($date->isToday() && $presence->status === 'not_checked_in' && $isPastLateThreshold) {
                    $presence->status = 'absent';
                    $presence->save();
                }
                $attendances->push($presence);
                $processedEmployees[] = $presence->employee_id;
            }
        }
        foreach ($onLeaveEmployees as $employeeId) {
            if (!in_array($employeeId, $processedEmployees)) {
                $employee = Employee::find($employeeId);
                if ($employee) {
                    $attendance = new Presence();
                    $attendance->date = $date;
                    $attendance->status = 'on_leave';
                    $attendance->employee_id = $employeeId;
                    $attendance->employee = $employee;
                    $attendance->is_on_leave = true;
                    $attendances->push($attendance);
                    $processedEmployees[] = $employeeId;
                }
            }
        }
        if ($date->isToday()) {
            // Check if today is a holiday or weekend
            $isHoliday = false;
            $holidayName = null;
            if (class_exists('App\\Models\\Holiday')) {
                $holiday = \App\Models\Holiday::getHoliday($date);
                $isHoliday = $date->isWeekend() || $holiday;
                $holidayName = $holiday ? $holiday->name : null;
            } else {
                $isHoliday = $date->isWeekend();
            }
            foreach ($employees as $employee) {
                if ($isHoliday) {
                    // Only push if not already processed (avoid duplicate in collection)
                    if (!in_array($employee->employee_id, $processedEmployees)) {
                        $attendance = Presence::where('employee_id', $employee->employee_id)
                            ->whereDate('date', $date)
                            ->first();
                        if ($attendance) {
                            $attendance->employee = $employee;
                            $attendances->push($attendance);
                            $processedEmployees[] = $employee->employee_id;
                        }
                    }
                } else if (!in_array($employee->employee_id, $processedEmployees)) {
                    $attendance = Presence::create([
                        'employee_id' => $employee->employee_id,
                        'date' => $date,
                        'status' => 'not_checked_in',
                        'check_in' => null,
                        'check_out' => null
                    ]);
                    // If it's past late threshold, update to absent
                    if ($isPastLateThreshold) {
                        $attendance->status = 'absent';
                        $attendance->save();
                    }
                    $attendance->employee = $employee;
                    $attendances->push($attendance);
                    $processedEmployees[] = $employee->employee_id;
                }
            }
        } else if ($date->isBefore($today)) {
            $existingRecords = $attendances->pluck('employee_id')->toArray();
            $employeesAtDate = $employees->filter(function($employee) use ($date) {
                return Carbon::parse($employee->date_joined)->lte($date);
            });
            foreach ($employeesAtDate as $employee) {
                if (!in_array($employee->employee_id, $existingRecords)) {
                    $attendance = new Presence();
                    $attendance->date = $date;
                    $attendance->status = 'absent';
                    $attendance->employee_id = $employee->employee_id;
                    $attendance->employee = $employee;
                    $attendances->push($attendance);
                }
            }
        }
        if ($request->filled('status')) {
            $attendances = $attendances->filter(function ($attendance) use ($request) {
                if ($request->status === 'on_leave') {
                    return ($attendance->status === 'on_leave') || (isset($attendance->is_on_leave) && $attendance->is_on_leave);
                }
                if ($request->status === 'not_checked_in') {
                    return $attendance->status === 'not_checked_in';
                }
                return $attendance->status === $request->status;
            });
        }
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $attendances = $attendances->filter(function ($attendance) use ($search) {
                $name = strtolower($attendance->employee->name ?? '');
                $email = strtolower($attendance->employee->email ?? '');
                return strpos($name, $search) !== false || strpos($email, $search) !== false;
            });
        }
        $attendances = $attendances->sortByDesc(function ($attendance) {
            return $attendance->updated_at ?? Carbon::now();
        })->values();
        $page = $request->get('page', 1);
        $perPage = 10;
        $items = $attendances->forPage($page, $perPage);
        $attendances = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $attendances->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        $searchTerm = $request->search ?? '';
        return view('admin.attendance._table', compact('attendances', 'searchTerm'))->render();
    }
}