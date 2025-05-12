@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">
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
        <h2 class="section-title font-bold text-indigo-700 mb-8">Kehadiran Karyawan</h2>
        <!-- Filters -->
        <form id="attendanceFilterForm" method="GET" action="{{ route('admin.attendance.index') }}" class="flex gap-2 mb-4">
            <div class="w-72 bg-white rounded-xl shadow-lg p-4">
                <label for="date" class="block text-base font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date"
                       id="date"
                       name="date"
                       value="{{ request('date', now()->format('Y-m-d')) }}"
                       class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base"
                       onchange="this.form.submit()">
            </div>
            <div class="w-72 bg-white rounded-xl shadow-lg p-4">
                <label for="status" class="block text-base font-medium text-gray-700 mb-2">Status</label>
                <select id="status"
                        name="status"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base"
                        onchange="this.form.submit()">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>Cuti</option>
                    <option value="not_checked_in" {{ request('status') === 'not_checked_in' ? 'selected' : '' }}>Belum Presensi</option>
                </select>
            </div>
            <div class="flex-1 bg-white rounded-xl shadow-lg p-4">
                <label for="search" class="block text-base font-medium text-gray-700 mb-2">Cari Karyawan</label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Masukkan nama atau email karyawan"
                           class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base pr-10"
                           onkeydown="if(event.key==='Enter'){this.form.submit();}">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-8">
            <div class="overflow-x-auto" id="attendance-table-area">
                @include('admin.attendance._table', compact('employees', 'presences', 'date', 'status', 'search'))
            </div>
        </div>
        <div class="mt-8">
            {{-- Pagination is now inside the table partial --}}
        </div>
    </div>
</div>
<script>
// AJAX live search and pagination for smooth updates
let searchTimeout;
const form = document.getElementById('attendanceFilterForm');
const tableArea = document.getElementById('attendance-table-area');

function updateTableWithAjax(params) {
    fetch('/admin/attendance/ajax-table?' + params, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.text())
        .then(html => {
            tableArea.innerHTML = html;
        });
}

document.getElementById('search').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        const params = new URLSearchParams(new FormData(form)).toString();
        updateTableWithAjax(params);
    }, 400);
});

document.getElementById('date').addEventListener('change', function() {
    const params = new URLSearchParams(new FormData(form)).toString();
    updateTableWithAjax(params);
});
document.getElementById('status').addEventListener('change', function() {
    const params = new URLSearchParams(new FormData(form)).toString();
    updateTableWithAjax(params);
});

tableArea.addEventListener('click', function(e) {
    const link = e.target.closest('.pagination a');
    if (link) {
        e.preventDefault();
        const url = new URL(link.href);
        const params = url.searchParams.toString();
        updateTableWithAjax(params);
        tableArea.scrollIntoView({ behavior: 'smooth' });
    }
});
</script>
@endsection
