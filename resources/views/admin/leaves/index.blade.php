@extends('layouts.app')

@section('title', 'Leave Request Management')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .section-title { font-size: 1.7rem; }
    .table-header { font-size: 1rem; }
    .table-cell { font-size: 1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-indigo-700 mb-8">Daftar Pengajuan Cuti</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-8">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-64 whitespace-nowrap">Karyawan</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Jenis Cuti</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Durasi</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-48 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 table-cell whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium text-lg">
                                                {{ strtoupper(substr($leave->employee->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 whitespace-nowrap">{{ $leave->employee->name }}</div>
                                        <div class="text-sm text-gray-500 whitespace-nowrap">{{ $leave->employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 table-cell whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                                    @if($leave->type === 'annual') bg-blue-100 text-blue-800
                                    @elseif($leave->type === 'sick') bg-green-100 text-green-800
                                    @elseif($leave->type === 'emergency') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif whitespace-nowrap">
                                    {{ $leave->type === 'annual' ? 'Cuti Tahunan' : ($leave->type === 'sick' ? 'Cuti Sakit' : ($leave->type === 'emergency' ? 'Cuti Darurat' : ucfirst($leave->type))) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 table-cell text-gray-500 whitespace-nowrap">
                                {{ $leave->duration }} hari
                            </td>
                            <td class="px-4 py-3 table-cell whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                                    {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($leave->status === 'rejected' || $leave->status === 'declined' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }} whitespace-nowrap">
                                    @if($leave->status === 'pending')
                                        Menunggu Persetujuan
                                    @elseif($leave->status === 'approved')
                                        Disetujui
                                    @elseif($leave->status === 'rejected' || $leave->status === 'declined')
                                        Ditolak
                                    @else
                                        {{ ucfirst($leave->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 table-cell font-medium whitespace-nowrap">
                                <div class="flex gap-2">
                                    <button type="button" onclick="showLeaveDetails({{ $leave->leave_id }}, '{{ $leave->type }}', '{{ $leave->start_date->format('Y-m-d') }}', '{{ $leave->end_date->format('Y-m-d') }}', '{{ $leave->status }}', `{{ addslashes($leave->reason) }}`, '{{ $leave->employee->name }}', '{{ $leave->duration }}')" class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        Detail
                                    </button>
                                    @if($leave->status === 'pending')
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave->leave_id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-1.5 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leaves.reject', $leave->leave_id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap">
                                                Tolak
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 table-cell text-center text-gray-500 whitespace-nowrap">
                                Belum ada daftar permintaan cuti.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $leaves->links() }}
        </div>
    </div>
</div>

<!-- Modal for Leave Details -->
<div id="leaveDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative mx-auto p-5 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <!-- Close (X) Icon Button -->
        <button type="button" id="closeModalIcon" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
        <div class="mt-3">
            <h3 class="text-xl font-bold leading-6 text-indigo-700 mb-6 flex items-center gap-2">
                Detail Pengajuan Cuti
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
            </h3>
            <div class="space-y-4 px-2 py-2">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Nama Karyawan</span><br>
                        <span id="employeeName" class="font-medium"></span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Tipe</span><br>
                        <span id="leaveTypeBadge" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Tanggal</span><br>
                        <span id="leaveDateRange" class="font-medium"></span>
                    </div>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 ml-2">
                        <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 256 256"><path d="M128,40a96,96,0,1,0,96,96A96.11,96.11,0,0,0,128,40Zm0,176a80,80,0,1,1,80-80A80.09,80.09,0,0,1,128,216ZM173.66,90.34a8,8,0,0,1,0,11.32l-40,40a8,8,0,0,1-11.32-11.32l40-40A8,8,0,0,1,173.66,90.34ZM96,16a8,8,0,0,1,8-8h48a8,8,0,0,1,0,16H104A8,8,0,0,1,96,16Z"></path></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Durasi</span><br>
                        <span id="leaveDuration" class="font-medium"></span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100">
                        <svg id="statusIcon" class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <!-- Default icon will be replaced by JavaScript -->
                        </svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Status</span><br>
                        <span id="leaveStatusBadge" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                    </span>
                    <div class="flex-1">
                        <span class="text-xs text-gray-400">Alasan</span><br>
                        <span id="leaveReason" class="block mt-1 text-gray-700"></span>
                    </div>
                </div>
            </div>
            <div class="items-center px-4 py-3 mt-4">
                <button id="closeModal" class="px-4 py-2 bg-indigo-600 text-white text-base font-semibold rounded-xl w-full shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function translateLeaveType(type) {
        type = type.toLowerCase();
        if (type === 'annual') return 'Cuti Tahunan';
        if (type === 'sick') return 'Cuti Sakit';
        if (type === 'emergency') return 'Cuti Darurat';
        return type.charAt(0).toUpperCase() + type.slice(1);
    }
    function translateLeaveStatus(status) {
        if (status === 'approved') return 'Disetujui';
        if (status === 'rejected') return 'Ditolak';
        return 'Menunggu Persetujuan';
    }
    function badgeColor(type) {
        if (type === 'Cuti Tahunan') return 'bg-blue-100 text-blue-800';
        if (type === 'Cuti Sakit') return 'bg-green-100 text-green-800';
        if (type === 'Cuti Darurat') return 'bg-red-100 text-red-800';
        return 'bg-gray-100 text-gray-800';
    }
    function statusBadgeColor(status) {
        if (status === 'Disetujui') return 'bg-green-100 text-green-800';
        if (status === 'Ditolak') return 'bg-red-100 text-red-800';
        return 'bg-yellow-100 text-yellow-800';
    }
    function getStatusIcon(status) {
        const statusIcon = document.getElementById('statusIcon');
        const iconContainer = statusIcon.parentElement;
        
        if (status === 'approved') {
            iconContainer.className = 'inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100';
            statusIcon.className = 'w-5 h-5 text-green-500';
            statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />';
        } else if (status === 'rejected') {
            iconContainer.className = 'inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100';
            statusIcon.className = 'w-5 h-5 text-red-500';
            statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M7.498 15.25H4.372c-1.026 0-1.945-.694-2.054-1.715a12.137 12.137 0 0 1-.068-1.285c0-2.848.992-5.464 2.649-7.521C5.287 4.247 5.886 4 6.504 4h4.016a4.5 4.5 0 0 1 1.423.23l3.114 1.04a4.5 4.5 0 0 0 1.423.23h1.294M7.498 15.25c.618 0 .991.724.725 1.282A7.471 7.471 0 0 0 7.5 19.75 2.25 2.25 0 0 0 9.75 22a.75.75 0 0 0 .75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 0 0 2.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384m-10.253 1.5H9.7m8.075-9.75c.01.05.027.1.05.148.593 1.2.925 2.55.925 3.977 0 1.487-.36 2.89-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398-.306.774-1.086 1.227-1.918 1.227h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 0 0 .303-.54" />';
        } else {
            iconContainer.className = 'inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100';
            statusIcon.className = 'w-5 h-5 text-yellow-500';
            statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />';
        }
    }
    function showLeaveDetails(leaveId, type, startDate, endDate, status, reason, employeeName, duration) {
        const typeText = translateLeaveType(type);
        const statusText = translateLeaveStatus(status);
        document.getElementById('employeeName').textContent = employeeName;
        const typeBadge = document.getElementById('leaveTypeBadge');
        const statusBadge = document.getElementById('leaveStatusBadge');
        typeBadge.textContent = typeText;
        typeBadge.className = 'px-3 py-1 text-sm font-semibold rounded-full ' + badgeColor(typeText);
        statusBadge.textContent = statusText;
        statusBadge.className = 'px-3 py-1 text-sm font-semibold rounded-full ' + statusBadgeColor(statusText);
        
        // Show only one date if duration is 1 day
        if (duration === '1') {
            document.getElementById('leaveDateRange').textContent = startDate;
        } else {
            document.getElementById('leaveDateRange').textContent = startDate + ' - ' + endDate;
        }
        
        document.getElementById('leaveDuration').textContent = duration + ' hari';
        document.getElementById('leaveReason').textContent = reason;
        getStatusIcon(status);
        document.getElementById('leaveDetailsModal').classList.remove('hidden');
    }
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('leaveDetailsModal').classList.add('hidden');
    });
    // Add event listener for the close (X) icon
    document.getElementById('closeModalIcon').addEventListener('click', function() {
        document.getElementById('leaveDetailsModal').classList.add('hidden');
    });
    document.getElementById('leaveDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
