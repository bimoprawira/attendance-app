<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Gaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        // ─────────────────────────────────────────────────────────────
        //  A. Ambil parameter bulan   (default = bulan berjalan)
        // ─────────────────────────────────────────────────────────────
        $month = $request->input('month', now()->format('Y-m'));      // ex: 2025-06
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end   = $start->clone()->endOfMonth();

        // ─────────────────────────────────────────────────────────────
        //  B. Ambil karyawan + slip gaji bulan tsb, PAGINASI
        // ─────────────────────────────────────────────────────────────
        $employees = Employee::with(['gajis' => fn ($q) => $q->where('periode_bayar', $month)])
            ->paginate(10)            // <<– ganti get() → paginate()
            ->withQueryString();      // supaya ?month= ikut saat pindah halaman

        // ─────────────────────────────────────────────────────────────
        //  C. Hitung data presensi per karyawan
        // ─────────────────────────────────────────────────────────────
        $attendanceData = [];

        foreach ($employees as $employee) {
            $presences = $employee->presences()
                ->whereBetween('date', [$start, $end])
                ->get();

            $attendanceData[$employee->employee_id] = [
                'days_worked'  => $presences->whereIn('status', ['present', 'late', 'not_checked_in'])->count(),
                'absent_days'  => $presences->where('status', 'absent')->count(),
                'hadir'        => $presences->where('status', 'present')->count(),
                'telat'        => $presences->where('status', 'late')->count(),
                'absen'        => $presences->where('status', 'absent')->count(),
                'workdays'     => $start->diffInWeekdays($end) + 1,   // contoh hitung 5 × minggu
            ];
        }

        return view('admin.gaji.index', compact('employees', 'attendanceData', 'month'));
    }

    public function storeSlip(Request $request, Employee $employee)
    {
        $request->validate([
            'periode_bayar' => 'required|string',
            'komponen_tambahan' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
        ]);

        $gaji = Gaji::create([
            'employee_id' => $employee->employee_id,
            'periode_bayar' => Carbon::parse($request->periode_bayar)->format('Y-m'),
            'gaji_pokok' => $employee->gaji_pokok,
            'komponen_tambahan' => $request->komponen_tambahan ?? 0,
            'potongan' => $request->potongan ?? 0,
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.gaji.index')
            ->with('success', 'Slip gaji berhasil dibuat.');
    }

    public function printSlip(Employee $employee, Gaji $gaji)
    {
        if ($gaji->employee_id !== $employee->employee_id) {
            abort(404);
        }

        return view('employee.gaji.print', compact('gaji'));
    }

    public function employeeIndex()
    {
        $gajis = auth()->user()->employee->gajis()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.gaji.index', compact('gajis'));
    }

    public function employeePrintSlip(Gaji $gaji)
    {
        if ($gaji->employee_id !== auth()->user()->employee_id) {
            abort(404);
        }

        return view('employee.gaji.print', compact('gaji'));
    }

    public function myGaji()
    {
        $gajis = auth()->user()->gajis()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('employee.gaji.index', compact('gajis'));
    }
} 