@extends('layouts.app')

@section('title', 'Kehadiran')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .dashboard-title { font-size: 2.3rem; }
    .section-title { font-size: 1.7rem; }
    .table-header { font-size: 1rem; }
    .table-cell { font-size: 1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-3xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-indigo-700 mb-8 tracking-wide text-left">Kehadiran</h2>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Aturan Kehadiran -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 border border-gray-200">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-indigo-700 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Aturan Kehadiran
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center">
                    <div class="w-40 flex-shrink-0 text-gray-500 font-medium">Form Dibuka:</div>
                    <div class="flex-grow text-blue-600 font-semibold">{{ $formOpenTime }}</div>
                </div>
                <div class="flex items-center">
                    <div class="w-40 flex-shrink-0 text-gray-500 font-medium">Hadir:</div>
                    <div class="flex-grow text-green-600 font-semibold">Presensi masuk sebelum {{ $presentBefore }}</div>
                </div>
                <div class="flex items-center">
                    <div class="w-40 flex-shrink-0 text-gray-500 font-medium">Terlambat:</div>
                    <div class="flex-grow text-yellow-600 font-semibold">Presensi masuk antara {{ $presentBefore }} dan {{ $lateBefore }}</div>
                </div>
                <div class="flex items-center">
                    <div class="w-40 flex-shrink-0 text-gray-500 font-medium">Tidak Hadir:</div>
                    <div class="flex-grow text-red-600 font-semibold">Tidak presensi masuk sampai {{ $lateBefore }}</div>
                </div>
                <div class="flex flex-col pt-4 mt-2 border-t border-gray-200">
                    <div class="mb-2 text-gray-500 font-medium">Waktu Saat Ini</div>
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-4 shadow-sm">
                        <div id="currentTime" class="text-center">
                            <span id="timeDigits" class="font-mono text-3xl font-bold text-indigo-600"></span>
                            <span id="timeAmPm" class="text-sm font-semibold text-indigo-500 ml-2"></span>
                        </div>
                        <div class="text-center mt-1">
                            <span id="timeDate" class="text-xs text-gray-500"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Kehadiran -->
        <div class="bg-white rounded-2xl shadow-lg mb-8 border border-gray-200">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-indigo-700 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Status Kehadiran
                </h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="text-gray-600">Status Saat Ini:</div>
                        <div class="font-medium mt-1 text-lg">
                            @if($status === 'present')
                                <span class="text-green-600">Hadir</span>
                            @elseif($status === 'late')
                                <span class="text-yellow-600">Terlambat</span>
                            @elseif($status === 'absent')
                                <span class="text-red-600">Tidak Hadir</span>
                            @elseif($status === 'on_leave')
                                <span class="text-purple-600">Cuti</span>
                            @else
                                @if(now()->format('H:i') > $lateBefore)
                                    <span class="text-red-600">Tidak Hadir</span>
                                @else
                                    <span class="text-gray-600">Belum Presensi</span>
                                @endif
                            @endif
                        </div>
                        @if($presence)
                            <div class="mt-2 text-sm text-gray-500">
                                @if($presence->check_in)
                                    <div>Waktu check-in: <span class="font-semibold text-indigo-600">{{ optional($presence->check_in)->format('H:i') ?? '-' }}</span></div>
                                @endif
                                @if($presence->check_out)
                                    <div>Waktu check-out: <span class="font-semibold text-indigo-600">{{ optional($presence->check_out)->format('H:i') ?? '-' }}</span></div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 md:items-end">
                        @php
                            $currentTime = \Carbon\Carbon::now();
                            $lateThreshold = \Carbon\Carbon::createFromTimeString($lateBefore);
                            $isPastLateThreshold = $currentTime->gt($lateThreshold);
                            $formOpenTime = \Carbon\Carbon::createFromTimeString($formOpenTime);
                            $isFormOpen = $currentTime->gte($formOpenTime);
                        @endphp
                        @if($status === 'on_leave')
                            <span class="text-blue-500 italic">Anda sedang cuti hari ini.</span>
                        @elseif($status === 'absent')
                            <span class="text-red-500 italic">Presensi tidak tersedia lagi. Anda ditandai sebagai tidak hadir hari ini.</span>
                        @elseif($isPastLateThreshold && !$presence->check_in)
                            <span class="text-red-500 italic">Presensi tidak tersedia lagi. Anda ditandai sebagai tidak hadir hari ini.</span>
                        @elseif(!$presence->check_in)
                            @if(!$isFormOpen)
                                <span class="text-blue-500 italic">Presensi masuk akan tersedia mulai {{ $formOpenTime }}</span>
                            @else
                                <form action="{{ route('presence.checkIn') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                        Presensi Masuk
                                    </button>
                                </form>
                            @endif
                        @elseif(!$presence->check_out && in_array($status, ['present', 'late']))
                            <form action="{{ route('presence.checkOut') }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                    Presensi Keluar
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 italic">Anda telah menyelesaikan kehadiran hari ini</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-right">
            <a href="{{ route('presence.history') }}"
               class="text-blue-600 hover:text-blue-800 font-semibold transition">
                Lihat Riwayat Kehadiran &rarr;
            </a>
        </div>
    </div>
</div>

<script>
function updateClock() {
    const now = new Date();

    // Format time
    const hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHours = String(hours % 12 || 12).padStart(2, '0');

    // Format date
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();

    // Update DOM
    document.getElementById('timeDigits').textContent = `${displayHours}:${minutes}:${seconds}`;
    document.getElementById('timeAmPm').textContent = ampm;
    document.getElementById('timeDate').textContent = `${day}, ${month} ${date}, ${year}`;
}

// Update clock immediately and then every second
updateClock();
setInterval(updateClock, 1000);
</script>

@endsection
