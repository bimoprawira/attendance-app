@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .dashboard-title { font-size: 2.3rem; }
    .section-title { font-size: 1.5rem; }
    .table-header { font-size: 1rem; }
    .table-cell { font-size: 1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="dashboard-title font-bold text-indigo-700 mb-2 tracking-wide text-left">Dashboard Karyawan</h2>
            <div class="text-base text-gray-600 mb-1">Selamat Datang, {{ auth()->user()->name }}!</div>
        </div>
        <!-- Profil Karyawan Card -->
        <div class="flex flex-col md:flex-row items-center bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 rounded-2xl shadow-xl p-8 mb-10 gap-8">
            <div class="flex-shrink-0">
                <div class="h-28 w-28 rounded-full bg-white border-4 border-blue-200 flex items-center justify-center overflow-hidden shadow-lg">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ auth()->user()->profile_picture }}" alt="Profile Picture" class="object-cover h-full w-full">
                    @else
                        <span class="text-5xl font-bold text-blue-500">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    @endif
                </div>
            </div>
            <div class="flex-1 text-white">
                <div class="text-2xl font-bold mb-1">{{ auth()->user()->name }}</div>
                <div class="mb-2 text-lg opacity-90">{{ auth()->user()->position }}</div>
                <div class="flex flex-wrap gap-4 text-base opacity-90">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-white opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ auth()->user()->date_joined->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-white opacity-80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mb-10">
            <!-- Kehadiran -->
            <div class="bg-blue-50 rounded-xl p-7 flex flex-col items-start shadow">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4 1 1-4L16.862 3.487z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-2-2"/></svg>
                    <h2 class="text-lg font-semibold text-blue-700">Kehadiran</h2>
                </div>
                <a href="{{ route('presence.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded mb-2 font-medium transition">Cek Kehadiran</a>
                <a href="{{ route('presence.history') }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-2 rounded font-medium transition">Lihat Riwayat</a>
            </div>
            <!-- Manajemen Cuti -->
            <div class="bg-yellow-50 rounded-xl p-7 flex flex-col items-start shadow">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h3.28a2 2 0 003.44 0H17a2 2 0 012 2v11a2 2 0 01-2 2z"/></svg>
                    <h2 class="text-lg font-semibold text-yellow-700">Manajemen Cuti</h2>
                </div>
                <button id="openLeaveModal" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded mb-2 font-medium transition">Ajukan Cuti</button>
                <a href="{{ route('leaves.index') }}" class="w-full bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-center py-2 rounded font-medium transition">Lihat Pengajuan</a>
            </div>
            <!-- Penggajian -->
            <div class="bg-green-50 rounded-xl p-7 flex flex-col items-start shadow">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 18H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5"/><path d="m16 19 3 3 3-3"/><path d="M18 12h.01"/><path d="M19 16v6"/><path d="M6 12h.01"/><circle cx="12" cy="12" r="2"/></svg>
                    <h2 class="text-lg font-semibold text-green-700">Penggajian</h2>
                </div>
                <a href="{{ route('gaji.index') }}" class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 rounded mb-2 font-medium transition">Lihat Riwayat Gaji</a>
                <a href="{{ route('gaji.export') }}" class="w-full bg-green-100 hover:bg-green-200 text-green-700 text-center py-2 rounded font-medium transition">Unduh Riwayat Gaji (Excel)</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajukan Cuti -->
<div id="leaveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative mx-auto p-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white">
        <form action="{{ route('leaves.store') }}" method="POST" class="p-8">
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-indigo-700">Ajukan Cuti</h2>
                <button type="button" id="closeLeaveModal" class="text-gray-400 hover:text-red-500 text-2xl font-bold">&times;</button>
            </div>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-6">
                <label for="type" class="block text-gray-700 text-base font-semibold mb-2">Jenis Cuti</label>
                <select name="type" id="type" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-blue-500 focus:ring-blue-500 p-3 text-base">
                    <option value="annual">Cuti Tahunan</option>
                    <option value="sick">Cuti Sakit</option>
                    <option value="emergency">Cuti Darurat</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_date" class="block text-gray-700 text-base font-semibold mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-blue-500 focus:ring-blue-500 p-3 text-base" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                </div>
                <div>
                    <label for="end_date" class="block text-gray-700 text-base font-semibold mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" id="end_date" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-blue-500 focus:ring-blue-500 p-3 text-base" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                </div>
            </div>
            <div class="mb-8">
                <label for="reason" class="block text-gray-700 text-base font-semibold mb-2">Alasan</label>
                <textarea name="reason" id="reason" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-blue-500 focus:ring-blue-500 p-3 text-base" required placeholder="Mohon berikan alasan rinci atas permintaan cuti Anda." oninvalid="this.setCustomValidity('Mohon isi alasan cuti Anda.')" oninput="this.setCustomValidity('')"></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelLeaveModal" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-xl">
                    Batalkan
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow transition">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Cuti -->
<div id="leaveDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative mx-auto p-5 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <!-- Close (X) Icon Button -->
        <button type="button" id="closeModalIcon" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
        <div class="mt-3">
            <h3 class="text-xl font-bold leading-6 text-indigo-700 mb-6 flex items-center gap-2">
                Detail Pengajuan Cuti
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
            </h3>
            <div class="space-y-6 px-2">
                <!-- Employee Name -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Nama Karyawan</span><br>
                        <span id="employeeName" class="font-medium"></span>
                    </div>
                </div>

                <!-- Leave Type -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 4H7a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h3.28a2 2 0 0 0 3.44 0H17a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Tipe</span><br>
                        <span id="leaveTypeBadge" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Tanggal</span><br>
                        <span id="leaveDateRange" class="font-medium"></span>
                    </div>
                </div>

                <!-- Duration -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Durasi</span><br>
                        <span id="leaveDuration" class="font-medium"></span>
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100">
                        <svg id="statusIcon" class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Status</span><br>
                        <span id="leaveStatusBadge" class="px-3 py-1 text-sm font-semibold rounded-full"></span>
                    </div>
                </div>

                <!-- Reason -->
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                    </span>
                    <div>
                        <span class="text-xs text-gray-400">Alasan</span><br>
                        <span id="leaveReason" class="font-medium"></span>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="button" id="closeModal" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl transition">
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
        const icon = document.getElementById('statusIcon');
        let path = '';
        
        switch(status) {
            case 'approved':
                path = 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z';
                break;
            case 'rejected':
                path = 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                break;
            default: // pending
                path = 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z';
        }
        
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="${path}" />`;
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
        
        // Format dates to Indonesian format
        const start = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        const end = new Date(endDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        if (duration === 1) {
            document.getElementById('leaveDateRange').textContent = start;
        } else {
            document.getElementById('leaveDateRange').textContent = `${start} - ${end}`;
        }
        
        document.getElementById('leaveDuration').textContent = `${duration} hari`;
        document.getElementById('leaveReason').textContent = reason;
        getStatusIcon(status);
        document.getElementById('leaveDetailsModal').classList.remove('hidden');
    }

    // Modal event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const detailsModal = document.getElementById('leaveDetailsModal');
        const closeModalBtn = document.getElementById('closeModal');
        const closeModalIcon = document.getElementById('closeModalIcon');

        function closeDetailsModal() {
            detailsModal.classList.add('hidden');
        }

        closeModalBtn.addEventListener('click', closeDetailsModal);
        closeModalIcon.addEventListener('click', closeDetailsModal);
        detailsModal.addEventListener('click', function(e) {
            if (e.target === detailsModal) {
                closeDetailsModal();
            }
        });

        // Existing modal code for leave application
        const modal = document.getElementById('leaveModal');
        const openButton = document.getElementById('openLeaveModal');
        const closeButton = document.getElementById('closeLeaveModal');
        const cancelButton = document.getElementById('cancelLeaveModal');

        function openModal() {
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        openButton.addEventListener('click', openModal);
        closeButton.addEventListener('click', closeModal);
        cancelButton.addEventListener('click', closeModal);

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
</script>
@endpush
@endsection
