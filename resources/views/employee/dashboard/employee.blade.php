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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-pen-line-icon lucide-clipboard-pen-line w-6 h-6 text-blue-600"><rect width="8" height="4" x="8" y="2" rx="1"/><path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5"/><path d="M16 4h2a2 2 0 0 1 1.73 1"/><path d="M8 18h1"/><path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/></svg>
                    <h2 class="text-lg font-semibold text-blue-700">Kehadiran</h2>
                </div>
                <a href="{{ route('presence.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded mb-2 font-medium transition">Cek Kehadiran</a>
                <a href="{{ route('presence.history') }}" class="w-full bg-blue-100 hover:bg-blue-200 text-blue-700 text-center py-2 rounded font-medium transition">Lihat Riwayat</a>
            </div>
            <!-- Manajemen Cuti -->
            <div class="bg-yellow-50 rounded-xl p-7 flex flex-col items-start shadow">
                <div class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-business-icon lucide-briefcase-business w-6 h-6 text-yellow-600"><path d="M12 12h.01"/><path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M22 13a18.15 18.15 0 0 1-20 0"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                    <h2 class="text-lg font-semibold text-yellow-700">Manajemen Cuti</h2>
                </div>
                <a href="{{ route('leaves.create') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 rounded mb-2 font-medium transition">Ajukan Cuti</a>
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
@endsection
