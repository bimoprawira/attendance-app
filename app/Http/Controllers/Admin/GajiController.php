<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gaji;
use App\Models\Employee;
use App\Exports\GajiExport;
use Maatwebsite\Excel\Facades\Excel;

class GajiController extends Controller
{
    // Halaman admin lihat semua gaji
    public function index()
    {
        $gajis = Gaji::with('employee')->get();
        return view('admin.gaji.index', compact('gajis'));
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

        return view('gaji.index', compact('gajis'));
    }

    // Export gaji (employee)
    public function export()
    {
        return Excel::download(new GajiExport(auth()->user()->employee_id), 'gaji.xlsx');
    }
}
