@extends('layouts.app')

@section('title', 'Slip Gaji')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .section-title { font-size: 1.7rem; }
    .subtitle { font-size: 1.1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-5xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-indigo-700 mb-2">Slip Gaji Saya</h2>
        <div class="text-base text-gray-600 mb-8">Lihat dan unduh slip gaji Anda di bawah ini.</div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($gajis->count() > 0)
            <div class="space-y-8">
                @foreach($gajis as $gaji)
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-500">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/20">
                                <!-- Calendar Icon -->
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="4"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            </span>
                            <div>
                                <div class="text-lg font-bold text-white tracking-wide">{{ indoMonthYear($gaji->periode_bayar) }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-white/80">Status:</span>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                        @if($gaji->status === 'approved' || $gaji->status === 'selesai') bg-green-100 text-green-700
                                        @elseif($gaji->status === 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        @if($gaji->status === 'approved' || $gaji->status === 'selesai')
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($gaji->status === 'pending')
                                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
                                        @else
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h4"/></svg>
                                        @endif
                                        {{ ucfirst($gaji->status) === 'Selesai' ? 'Selesai' : ucfirst($gaji->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button onclick="printSlip({{ $gaji->id_gaji ?? 'null' }})" 
                            class="px-5 py-2 bg-white text-indigo-700 font-semibold rounded-xl shadow hover:bg-indigo-50 border border-indigo-200 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17v4H7v-4M7 17V7a2 2 0 012-2h6a2 2 0 012 2v10m-6 0h6"/></svg>
                            Cetak Slip
                        </button>
                    </div>
                    <!-- Card Body -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 px-8 py-6 bg-white">
                        <div>
                            <h4 class="text-base font-semibold text-indigo-700 mb-3">Rincian Gaji</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-gray-600">
                                    <span>Gaji Pokok</span>
                                    <span class="font-medium text-gray-900">Rp{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tambahan</span>
                                    <span class="font-medium text-gray-900">Rp{{ number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Potongan</span>
                                    <span class="font-medium text-gray-900">Rp{{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="mt-5">
                                <div class="flex justify-between items-center bg-indigo-50 rounded-xl px-4 py-3">
                                    <span class="text-base font-bold text-indigo-700">Total Gaji</span>
                                    <span class="text-xl font-extrabold text-indigo-700">
                                        Rp{{ number_format($gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="md:border-l border-gray-200 pl-0 md:pl-8 mt-8 md:mt-0">
                            <h4 class="text-base font-semibold text-indigo-700 mb-3">Informasi Karyawan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama</span>
                                    <span class="font-medium text-gray-900">{{ $gaji->employee->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jabatan</span>
                                    <span class="font-medium text-gray-900">{{ $gaji->employee->position }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Cetak</span>
                                    <span class="font-medium text-gray-900">{{ $gaji->created_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada slip gaji tersedia.</p>
        @endif
    </div>
</div>

<script>
function printSlip(gajiId) {
    if (!gajiId || gajiId === 'null') {
        alert('Slip gaji tidak ditemukan!');
        return;
    }
    window.open(`/employee/gaji/${gajiId}/print`, '_blank');
}

@php
function indoMonthYear($ym) {
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei',
        '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober',
        '11' => 'November', '12' => 'Desember'
    ];
    if (preg_match('/^(\d{4})-(\d{2})$/', $ym, $m)) {
        return $bulan[$m[2]] . ' ' . $m[1];
    }
    return $ym;
}
@endphp
</script>
@endsection
