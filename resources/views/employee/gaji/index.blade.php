@extends('layouts.app')

@section('title', 'Riwayat Gaji')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .section-title { font-size: 1.7rem; }
    .subtitle { font-size: 1.1rem; }
    .salary-table th, .salary-table td { white-space: nowrap; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-5xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <h2 class="section-title font-bold text-indigo-700 mb-2 tracking-wide text-left">Riwayat Gaji Saya</h2>
        <div class="subtitle text-base text-gray-600 mb-8">Lihat dan unduh riwayat gaji Anda di bawah ini.</div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($gajis->count() > 0)
            <div class="overflow-x-auto">
                <table class="salary-table min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Gaji Pokok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tambahan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Potongan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total Gaji</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($gajis as $gaji)
                        <tr>
                            <td class="px-6 py-3 text-sm text-gray-700">{{ $gaji->periode_bayar }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">Rp{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">Rp{{ number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm text-gray-700">Rp{{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-base font-bold text-indigo-700 bg-indigo-50 rounded-xl">
                                Rp{{ number_format($gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('gaji.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-xl shadow transition">
                    Unduh Excel
                </a>
            </div>
        @else
            <p class="text-gray-500">Belum ada data gaji tersedia.</p>
        @endif
    </div>
</div>
@endsection
