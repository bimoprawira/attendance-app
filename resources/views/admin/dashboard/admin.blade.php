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
                    @include('admin.attendance._table', [
                        'employees' => $dashboardEmployees,
                        'presences' => $dashboardPresences,
                        'date' => now()->toDateString(),
                        'status' => null,
                        'search' => null
                    ])
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-64 whitespace-nowrap">Karyawan</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Periode Gaji</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Total Gaji</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentPayrolls as $payroll)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap table-cell">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden">
                                                @if($payroll->employee->profile_picture)
                                                    <img src="{{ $payroll->employee->profile_picture }}" alt="Profile Picture" class="object-cover h-10 w-10 rounded-full">
                                                @else
                                                    <span class="text-indigo-600 font-medium text-lg">{{ strtoupper(substr($payroll->employee->name, 0, 1)) }}</span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $payroll->employee->name }}</div>
                                                <div class="text-gray-500">{{ $payroll->employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 table-cell">
                                        @php
                                            \Carbon\Carbon::setLocale('id');
                                            $periode = \Carbon\Carbon::createFromFormat('Y-m', $payroll->periode_bayar);
                                        @endphp
                                        {{ $periode->translatedFormat('F Y') }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-800 table-cell">
                                        Rp{{ number_format($payroll->gaji_pokok + $payroll->komponen_tambahan - $payroll->potongan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 table-cell text-center text-gray-500 whitespace-nowrap">
                                        Belum ada daftar gaji terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End Manajemen Gaji -->

        <!-- Modal for Leave Details -->
        <div id="leaveDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="relative mx-auto p-5 w-full max-w-md shadow-2xl rounded-2xl bg-white">
                <button type="button" id="closeModalIcon" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
                <div class="mt-3">
                    <h3 class="text-xl font-bold leading-6 text-indigo-700 mb-6 flex items-center gap-2">
                        Detail Pengajuan Cuti
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    </h3>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Nama Karyawan</span><br>
                                <span id="employeeName" class="font-medium"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Tanggal</span><br>
                                <span id="leaveDates" class="font-medium"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Durasi</span><br>
                                <span id="leaveDuration" class="font-medium"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Tipe</span><br>
                                <span id="leaveType" class="font-medium"></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Status</span><br>
                                <span id="leaveStatus" class="font-medium"></span>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                            <div>
                                <span class="text-xs text-gray-400">Alasan</span><br>
                                <span id="leaveReason" class="font-medium"></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8">
                        <button type="button" id="closeModal" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function showLeaveDetails(leaveId, type, startDate, endDate, status, reason, employeeName, duration) {
                // Format the leave type
                const formattedType = type === 'sick' ? 'Cuti Sakit' :
                                    type === 'annual' ? 'Cuti Tahunan' :
                                    type === 'emergency' ? 'Cuti Darurat' : type;
                
                // Format the status
                const formattedStatus = status === 'pending' ? 'Menunggu Persetujuan' :
                                      status === 'approved' ? 'Disetujui' :
                                      status === 'rejected' ? 'Ditolak' : status;
                
                // Format the dates
                const start = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                const end = new Date(endDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                
                // Update modal content
                document.getElementById('employeeName').textContent = employeeName;
                document.getElementById('leaveDates').textContent = `${start} - ${end}`;
                document.getElementById('leaveDuration').textContent = `${duration} hari`;
                document.getElementById('leaveType').textContent = formattedType;
                document.getElementById('leaveStatus').textContent = formattedStatus;
                document.getElementById('leaveReason').textContent = reason;
                
                // Show modal
                const modal = document.getElementById('leaveDetailsModal');
                modal.classList.remove('hidden');
                
                // Close modal handlers
                const closeModal = () => modal.classList.add('hidden');
                document.getElementById('closeModal').onclick = closeModal;
                document.getElementById('closeModalIcon').onclick = closeModal;
                
                // Close on click outside
                modal.onclick = (e) => {
                    if (e.target === modal) closeModal();
                };
            }
        </script>
    </div>
</div>
@endsection
