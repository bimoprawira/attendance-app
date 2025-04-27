@extends('layouts.app')

@section('title', 'Pengajuan Cuti')

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
    <div class="w-full max-w-4xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-indigo-700 mb-2 tracking-wide text-left">Manajemen Cuti</h2>
        <div class="text-base text-gray-600 mb-8">Kelola dan ajukan cuti Anda</div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Kuota Cuti Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="flex items-center bg-blue-50 rounded-xl p-5 shadow gap-4">
                <div class="bg-blue-200 text-blue-700 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 256 256">
                        <rect x="40" y="40" width="176" height="176" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <line x1="176" y1="24" x2="176" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <line x1="80" y1="24" x2="80" y2="56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <line x1="40" y1="88" x2="216" y2="88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <polyline points="88 128 104 120 104 184" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <path d="M138.14,128a16,16,0,1,1,26.64,17.63L136,184h32" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-blue-800 mb-1">Cuti Tahunan</div>
                    <div class="text-blue-600 font-bold text-lg">{{ Auth::user()->annual_leave_quota - Auth::user()->used_annual_leave }} <span class="font-normal text-sm">hari tersedia</span></div>
                    <div class="text-xs text-blue-500">Digunakan: {{ Auth::user()->used_annual_leave }} / {{ Auth::user()->annual_leave_quota }}</div>
                </div>
            </div>
            <div class="flex items-center bg-green-50 rounded-xl p-5 shadow gap-4">
                <div class="bg-green-200 text-green-700 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 256 256">
                        <line x1="32" y1="216" x2="248" y2="216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <path d="M48,216V48a8,8,0,0,1,8-8h96a8,8,0,0,1,8,8V216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <path d="M160,120h64a8,8,0,0,1,8,8v88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <line x1="104" y1="72" x2="104" y2="120" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <line x1="80" y1="96" x2="128" y2="96" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                        <polyline points="128 216 128 160 80 160 80 216" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-green-800 mb-1">Cuti Sakit</div>
                    <div class="text-green-600 font-bold text-lg">{{ Auth::user()->sick_leave_quota - Auth::user()->used_sick_leave }} <span class="font-normal text-sm">hari tersedia</span></div>
                    <div class="text-xs text-green-500">Digunakan: {{ Auth::user()->used_sick_leave }} / {{ Auth::user()->sick_leave_quota }}</div>
                </div>
            </div>
            <div class="flex items-center bg-red-50 rounded-xl p-5 shadow gap-4">
                <div class="bg-red-200 text-red-700 rounded-full p-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 18v-6a5 5 0 1 1 10 0v6"/>
                        <path d="M5 21a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2z"/>
                        <path d="M21 12h1"/>
                        <path d="M18.5 4.5 18 5"/>
                        <path d="M2 12h1"/>
                        <path d="M12 2v1"/>
                        <path d="m4.929 4.929.707.707"/>
                        <path d="M12 12v6"/>
                    </svg>
                </div>
                <div>
                    <div class="font-semibold text-red-800 mb-1">Cuti Darurat</div>
                    <div class="text-red-600 font-bold text-lg">{{ Auth::user()->emergency_leave_quota - Auth::user()->used_emergency_leave }} <span class="font-normal text-sm">hari tersedia</span></div>
                    <div class="text-xs text-red-500">Digunakan: {{ Auth::user()->used_emergency_leave }} / {{ Auth::user()->emergency_leave_quota }}</div>
                </div>
            </div>
        </div>

        <!-- Tombol Ajukan Cuti -->
        <div class="flex justify-end mb-6">
            <button id="openLeaveModal"
                class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-xl shadow transition">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                </svg>
                Ajukan Cuti
            </button>
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

        <!-- Tabel Pengajuan Cuti -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Tipe</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-48 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @foreach($leaves as $leave)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                                    @if($leave->type === 'annual') bg-blue-100 text-blue-800
                                    @elseif($leave->type === 'sick') bg-green-100 text-green-800
                                    @elseif($leave->type === 'emergency') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif whitespace-nowrap">
                                    @if($leave->type === 'annual')
                                        Cuti Tahunan
                                    @elseif($leave->type === 'sick')
                                        Cuti Sakit
                                    @elseif($leave->type === 'emergency')
                                        Cuti Darurat
                                    @else
                                        {{ ucfirst($leave->type) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                                    @if($leave->status === 'approved') bg-green-100 text-green-800
                                    @elseif($leave->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif whitespace-nowrap">
                                    @if($leave->status === 'approved')
                                        Disetujui
                                    @elseif($leave->status === 'rejected')
                                        Ditolak
                                    @else
                                        Menunggu Persetujuan
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <button type="button" onclick="showLeaveDetails({{ $leave->leave_id }}, '{{ $leave->type }}', '{{ $leave->start_date->format('Y-m-d') }}', '{{ $leave->end_date->format('Y-m-d') }}', '{{ $leave->status }}', `{{ addslashes($leave->reason) }}`, '{{ addslashes(Auth::user()->name) }}', {{ $leave->duration }})"
                                        class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail Cuti -->
<div id="leaveDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
    <div class="relative mx-auto p-5 w-full max-w-md shadow-2xl rounded-2xl bg-white">
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
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005H9.75v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" /></svg>
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
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full" id="statusIconContainer">
                        <svg id="statusIcon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
        const iconContainer = document.getElementById('statusIconContainer');
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
        if (duration === 1) {
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
    document.getElementById('leaveDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Modal logic
    document.getElementById('openLeaveModal').addEventListener('click', function() {
        document.getElementById('leaveModal').classList.remove('hidden');
    });
    document.getElementById('closeLeaveModal').addEventListener('click', function() {
        document.getElementById('leaveModal').classList.add('hidden');
    });
    document.getElementById('leaveModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    // Ensure end date is not before start date
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
    document.getElementById('cancelLeaveModal').addEventListener('click', function() {
        document.getElementById('leaveModal').classList.add('hidden');
    });
</script>
@endpush
@endsection
