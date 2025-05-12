<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Employee;
use App\Exports\GajiExport;
use App\Exports\AdminGajiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Presence;
use App\Models\Holiday;
use Carbon\Carbon;

class GajiController extends Controller
{
    // Halaman admin lihat semua gaji
    public function index(Request $request)
    {
        $month = $request->get('month') ?? date('Y-m');
        $currentMonth = \Carbon\Carbon::now()->format('Y-m');
        // Use Carbon for proper date comparison
        $selected = \Carbon\Carbon::createFromFormat('Y-m', $month);
        $current = \Carbon\Carbon::now();
        // Debug log
        \Log::info('GajiController@index month filter', [
            'selected' => $selected->format('Y-m'),
            'current' => $current->format('Y-m'),
        ]);
        $employees = Employee::with('gajis')->paginate(10)->appends($request->except('page'));

        // Calculate attendance data for each employee
        $attendanceData = [];
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        $start = Carbon::createFromDate($year, $monthNum, 1);
        $end = $start->copy()->endOfMonth();

        // Calculate workdays (exclude weekends and holidays)
        $workdays = 0;
        $totalDays = $start->daysInMonth;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekend() || Holiday::isHoliday($date)) {
                continue;
            }
            $workdays++;
        }

        // Find the earliest month with data (from gaji or presence)
        $firstGaji = Gaji::orderBy('periode_bayar', 'asc')->first();
        $firstPresence = Presence::orderBy('date', 'asc')->first();
        $firstMonth = null;
        if ($firstGaji && $firstPresence) {
            $firstMonth = min(
                Carbon::createFromFormat('Y-m', substr($firstGaji->periode_bayar, 0, 7)),
                Carbon::createFromFormat('Y-m', substr($firstPresence->date, 0, 7))
            );
        } elseif ($firstGaji) {
            $firstMonth = Carbon::createFromFormat('Y-m', substr($firstGaji->periode_bayar, 0, 7));
        } elseif ($firstPresence) {
            $firstMonth = Carbon::createFromFormat('Y-m', substr($firstPresence->date, 0, 7));
        } else {
            $firstMonth = $current;
        }
        // Only show data if selected month is between firstMonth and current (inclusive)
        if ($selected->lt($firstMonth) || $selected->gt($current)) {
            return view('admin.gaji.index', [
                'employees' => collect([]),
                'attendanceData' => [],
                'month' => $month,
            ]);
        }

        foreach ($employees as $employee) {
            $presences = Presence::where('employee_id', $employee->employee_id)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->get();
            $hadir = $presences->where('status', 'present')->count();
            $telat = $presences->where('status', 'late')->count();
            $absen = $presences->where('status', 'absent')->count();
            $onLeave = $presences->where('status', 'on_leave')->count();
            $libur = $presences->where('status', 'libur')->count();
            $notCheckedIn = $presences->where('status', 'not_checked_in')->count();
            // Hari absen = workdays - hadir - telat - on_leave
            $absentDays = max(0, $workdays - ($hadir + $telat + $onLeave));
            $attendanceData[$employee->employee_id] = [
                'hadir' => $hadir,
                'telat' => $telat,
                'absen' => $absen,
                'on_leave' => $onLeave,
                'libur' => $libur,
                'not_checked_in' => $notCheckedIn,
                'days_worked' => $hadir + $telat,
                'absent_days' => $absentDays,
                'workdays' => $workdays,
                'total_days' => $totalDays,
            ];
        }

        return view('admin.gaji.index', [
            'employees' => $employees,
            'attendanceData' => $attendanceData,
            'month' => $month,
        ]);
    }

    // Form tambah gaji
    public function create()
    {
        $karyawans = Employee::all();
        return view('admin.gaji.create', compact('karyawans'));
    }

    // Simpan data gaji
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'gaji_pokok' => 'required|numeric',
            'periode_bayar' => 'required|string',
        ]);

        Gaji::create($request->all());

        return redirect()->route('admin.gaji.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }

    // Employee melihat gaji sendiri
    public function myGaji()
    {
        $gajis = Gaji::where('employee_id', auth()->user()->employee_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('employee.gaji.index', compact('gajis'));
    }

    // Export gaji (employee)
    public function export()
    {
        return Excel::download(new GajiExport(auth()->user()->employee_id), 'gaji.xlsx');
    }

    // Export gaji (admin)
    public function exportAll()
    {
        $filename = 'data_gaji_karyawan_' . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new AdminGajiExport(), $filename);
    }
}
