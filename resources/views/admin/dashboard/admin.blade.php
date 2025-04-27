@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .dashboard-title { font-size: 2.3rem; }
    .section-title { font-size: 1.5rem; }
    .card-label { font-size: 1rem; }
    .card-number { font-size: 2.1rem; }
    .table-header { font-size: 1rem; }
    .table-cell { font-size: 1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="dashboard-title font-bold text-indigo-700 mb-8 tracking-wide text-left">Admin Dashboard</h2>
        <!-- Kehadiran Karyawan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="section-title font-semibold text-indigo-600">Kehadiran Karyawan</h3>
                <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center text-indigo-500 hover:text-indigo-700 text-base font-medium">
                    Lihat Semua
                    <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div class="bg-indigo-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Total Karyawan</div>
                    <div class="card-number font-bold text-indigo-700">{{ $totalEmployees }}</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Hadir Hari Ini</div>
                    <div class="card-number font-bold text-green-600">{{ $todayPresent }}</div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Terlambat Hari Ini</div>
                    <div class="card-number font-bold text-yellow-600">{{ $todayLate }}</div>
                </div>
                <div class="bg-red-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Tidak Hadir Hari Ini</div>
                    <div class="card-number font-bold text-red-600">{{ $todayAbsent }}</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Cuti</div>
                    <div class="card-number font-bold text-purple-600">{{ $todayOnLeave }}</div>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Kehadiran Terbaru</h4>
                <div class="overflow-x-auto">
                    @include('admin.attendance._table', ['attendances' => $recentAttendances])
                </div>
            </div>
        </div>
        <!-- End Kehadiran Karyawan -->

        <!-- Manajemen Cuti -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="section-title font-semibold text-yellow-600">Manajemen Cuti</h3>
                <a href="{{ route('admin.leaves.index') }}" class="inline-flex items-center text-yellow-500 hover:text-yellow-700 text-base font-medium">
                    Lihat Semua
                    <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                    <div class="card-label text-gray-500 mb-1">Permintaan Cuti Tertunda</div>
                    <div class="card-number font-bold text-yellow-600">{{ $pendingLeaves }}</div>
                </div>
            </div>
            <div class="mt-4">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Permintaan Cuti Terbaru</h4>
                <div class="overflow-x-auto">
                    @include('admin.leaves._table', ['leaves' => $recentLeaves])
                </div>
            </div>
        </div>
        <!-- End Manajemen Cuti -->

        <!-- Manajemen Gaji -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="section-title font-semibold text-blue-600">Manajemen Gaji</h3>
                <a href="{{ route('admin.gaji.index') }}" class="inline-flex items-center text-blue-500 hover:text-blue-700 text-base font-medium">
                    Lihat Semua
                    <svg class="ml-1 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="mt-2">
                <h4 class="text-lg font-semibold text-gray-700 mb-2">Gaji Terbaru</h4>
                @if($recentPayrolls->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-2 text-left table-header font-medium text-gray-500 uppercase">Karyawan</th>
                                    <th class="px-2 py-2 text-left table-header font-medium text-gray-500 uppercase">Periode Gaji</th>
                                    <th class="px-2 py-2 text-left table-header font-medium text-gray-500 uppercase">Total Gaji</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentPayrolls as $payroll)
                                    <tr>
                                        <td class="px-2 py-2 whitespace-nowrap table-cell">
                                            <div class="font-medium text-gray-900">{{ $payroll->employee->name }}</div>
                                            <div class="text-gray-500">{{ $payroll->employee->email }}</div>
                                        </td>
                                        <td class="px-2 py-2 text-gray-500 table-cell">{{ $payroll->periode_bayar }}</td>
                                        <td class="px-2 py-2 font-semibold text-gray-800 table-cell">
                                            Rp{{ number_format($payroll->gaji_pokok + $payroll->komponen_tambahan - $payroll->potongan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 table-cell">Belum Ada Gaji Terbaru.</p>
                @endif
            </div>
        </div>
        <!-- End Manajemen Gaji -->
    </div>
</div>
@endsection
