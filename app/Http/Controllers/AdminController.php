<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        $totalEmployees = Employee::where('role', 'employee')->count();
        $todayPresent = Presence::whereDate('date', Carbon::today())
            ->where('status', 'present')
            ->count();
        $todayLate = Presence::whereDate('date', Carbon::today())
            ->where('status', 'late')
            ->count();

        return view('admin.dashboard', compact('totalEmployees', 'todayPresent', 'todayLate'));
    }

    public function employees()
    {
        $query = Employee::where('role', 'employee');
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }
        $employees = $query->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    public function storeEmployee(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'password' => 'required|string|min:8',
            'position' => 'required|string|max:255',
            'date_joined' => 'required|date',
            'annual_leave_quota' => 'required|integer|min:0',
            'sick_leave_quota' => 'required|integer|min:0',
            'emergency_leave_quota' => 'required|integer|min:0',
        ]);

        Employee::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
            'position' => $validated['position'],
            'date_joined' => $validated['date_joined'],
            'annual_leave_quota' => $validated['annual_leave_quota'],
            'sick_leave_quota' => $validated['sick_leave_quota'],
            'emergency_leave_quota' => $validated['emergency_leave_quota'],
            'used_annual_leave' => 0,
            'used_sick_leave' => 0,
            'used_emergency_leave' => 0,
        ]);

        return redirect()->route('admin.employees')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function updateEmployee(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,' . $employee->employee_id . ',employee_id',
            'password' => 'nullable|string|min:8',
            'position' => 'required|string|max:255',
            'date_joined' => 'required|date',
            'annual_leave_quota' => 'required|integer|min:0',
            'sick_leave_quota' => 'required|integer|min:0',
            'emergency_leave_quota' => 'required|integer|min:0',
        ]);

        // Check if new quotas are less than used quotas
        if ($validated['annual_leave_quota'] < $employee->used_annual_leave) {
            return back()->withErrors(['annual_leave_quota' => 'Kuota cuti tahunan tidak boleh kurang dari kuota yang digunakan']);
        }
        if ($validated['sick_leave_quota'] < $employee->used_sick_leave) {
            return back()->withErrors(['sick_leave_quota' => 'Kuota cuti sakit tidak boleh kurang dari kuota yang digunakan']);
        }
        if ($validated['emergency_leave_quota'] < $employee->used_emergency_leave) {
            return back()->withErrors(['emergency_leave_quota' => 'Kuota cuti darurat tidak boleh kurang dari kuota yang digunakan']);
        }

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->position = $validated['position'];
        $employee->date_joined = $validated['date_joined'];
        $employee->annual_leave_quota = $validated['annual_leave_quota'];
        $employee->sick_leave_quota = $validated['sick_leave_quota'];
        $employee->emergency_leave_quota = $validated['emergency_leave_quota'];
        
        if ($validated['password']) {
            $employee->password = Hash::make($validated['password']);
        }

        $employee->save();

        return redirect()->route('admin.employees')->with('success', 'Data Karyawan berhasil diperbarui.');
    }

    public function deleteEmployee($id)
    {
        $employee = Employee::findOrFail($id);
        
        if ($employee->role !== 'employee') {
            return back()->with('error', 'Anda hanya dapat menghapus akun karyawan.');
        }

        $employee->delete();

        return redirect()->route('admin.employees')->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function ajaxEmployeesTable(Request $request)
    {
        $query = Employee::where('role', 'employee');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }
        $employees = $query->paginate(10);
        $searchTerm = $request->search ?? '';
        return view('admin.employees._table', compact('employees', 'searchTerm'))->render();
    }
}
