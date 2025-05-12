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
        $date = $request->filled('date') ? $request->date : now()->toDateString();
        $status = $request->status;
        $search = $request->search;

        // Get all presences for the selected date
        $presences = \App\Models\Presence::whereDate('date', $date)->get()->keyBy('employee_id');

        $employees = Employee::query();

        if ($search) {
            $employees->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($status) {
            if ($status === 'absent') {
                // Employees with NO presence for the selected date
                $employeeIdsWithPresence = $presences->keys();
                $employees->whereNotIn('employee_id', $employeeIdsWithPresence);
            } else {
                // Employees whose presence for the selected date matches the status
                $employeeIds = $presences->filter(function($presence) use ($status) {
                    return $presence->status === $status;
                })->keys();
                $employees->whereIn('employee_id', $employeeIds);
            }
        } else {
            // Only show employees who have a presence record for the selected date
            $employees->whereIn('employee_id', $presences->keys());
        }

        $employees = $employees->orderBy('name')->paginate(10)->appends($request->except('page'));

        return view('admin.attendance.index', compact('employees', 'presences', 'date', 'status', 'search'));
    }

    public function ajaxTable(Request $request)
    {
        $date = $request->filled('date') ? $request->date : now()->toDateString();
        $status = $request->status;
        $search = $request->search;

        // Get all presences for the selected date
        $presences = \App\Models\Presence::whereDate('date', $date)->get()->keyBy('employee_id');

        $employees = Employee::query();

        if ($search) {
            $employees->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($status) {
            if ($status === 'absent') {
                // Employees with NO presence for the selected date
                $employeeIdsWithPresence = $presences->keys();
                $employees->whereNotIn('employee_id', $employeeIdsWithPresence);
            } else {
                // Employees whose presence for the selected date matches the status
                $employeeIds = $presences->filter(function($presence) use ($status) {
                    return $presence->status === $status;
                })->keys();
                $employees->whereIn('employee_id', $employeeIds);
            }
        } else {
            // Only show employees who have a presence record for the selected date
            $employees->whereIn('employee_id', $presences->keys());
        }

        $employees = $employees->orderBy('name')->paginate(10)->appends($request->except('page'));

        return view('admin.attendance._table', compact('employees', 'presences', 'date', 'status', 'search'))->render();
    }
}