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
        <div class="flex gap-2 mb-4">
            <!-- Tanggal Filter -->
            <div class="w-72 bg-white rounded-xl shadow-lg p-4">
                <label for="date" class="block text-base font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date"
                       id="date"
                       name="date"
                       value="{{ request('date', now()->format('Y-m-d')) }}"
                       placeholder="mm/dd/yyyy"
                       onchange="applyFilters()"
                       class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
            </div>

            <!-- Status Filter -->
            <div class="w-72 bg-white rounded-xl shadow-lg p-4">
                <label for="status" class="block text-base font-medium text-gray-700 mb-2">Status</label>
                <select id="status"
                        name="status"
                        onchange="applyFilters()"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                    <option value="" {{ request('status') === '' ? 'selected' : '' }}>Semua Status</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Hadir</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                    <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Tidak Hadir</option>
                    <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>Cuti</option>
                    <option value="not_checked_in" {{ request('status') === 'not_checked_in' ? 'selected' : '' }}>Belum Presensi</option>
                </select>
            </div>

            <!-- Search Filter -->
            <div class="flex-1 bg-white rounded-xl shadow-lg p-4">
                <label for="search" class="block text-base font-medium text-gray-700 mb-2">Cari Karyawan</label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Masukkan nama atau email karyawan"
                           onchange="applyFilters()"
                           class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base pr-10">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-8">
            <div class="overflow-x-auto" id="attendance-table-area">
                @include('admin.attendance._table', ['attendances' => $attendances, 'searchTerm' => request('search', '')])
            </div>
        </div>
        <div class="mt-8">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
<script>
function applyFilters() {
    const date = document.getElementById('date').value;
    const status = document.getElementById('status').value;
    const search = document.getElementById('search').value;

    // Build query string
    const params = new URLSearchParams();
    if (date) params.set('date', date);
    if (status) params.set('status', status);
    if (search) params.set('search', search);

    fetch(`/admin/attendance/ajax-table?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('attendance-table-area').innerHTML = html;
        });
}

document.getElementById('search').addEventListener('input', applyFilters);
document.getElementById('date').addEventListener('change', applyFilters);
document.getElementById('status').addEventListener('change', applyFilters);

// Initialize datepicker with today's date if no date is selected
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    if (dateInput && !dateInput.value) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;
    }

    // Initialize highlight.js
    document.querySelectorAll('pre code').forEach((block) => {
        hljs.highlightBlock(block);
    });

    // Initialize highlight.js for dynamic content
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // ELEMENT_NODE
                    node.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightBlock(block);
                    });
                }
            });
        });
    });

    // Start observing the attendance table area for changes
    observer.observe(document.getElementById('attendance-table-area'), {
        childList: true,
        subtree: true
    });
});
</script>
@endsection
