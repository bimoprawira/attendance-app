@extends('layouts.app')

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
        <div class="flex justify-between items-center mb-8">
            <h2 class="section-title font-bold text-indigo-700 mb-0 tracking-wide text-left">Riwayat Kehadiran</h2>
            <a href="{{ route('presence.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition">
                <span class="mr-1">&larr;</span>
                Kembali ke Kehadiran
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Tanggal</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Presensi Masuk</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Presensi Keluar</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($presences as $presence)
                            <tr>
                                <td class="px-4 py-3 text-gray-500 table-cell">{{ optional($presence->date)->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500 table-cell">{{ optional($presence->check_in)->format('H:i') ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-500 table-cell">{{ optional($presence->check_out)->format('H:i') ?? '-' }}</td>
                                <td class="px-4 py-3 table-cell">
                                    <span class="px-2 inline-flex text-base leading-6 font-semibold rounded-full
                                        @if($presence->status === 'present') bg-green-100 text-green-800
                                        @elseif($presence->status === 'late') bg-yellow-100 text-yellow-800
                                        @elseif($presence->status === 'on_leave') bg-purple-100 text-purple-800
                                        @elseif($presence->status === 'not_checked_in') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($presence->status === 'on_leave')
                                            Cuti
                                        @elseif($presence->status === 'not_checked_in')
                                            Belum Presensi
                                        @elseif($presence->status === 'present')
                                            Hadir
                                        @elseif($presence->status === 'late')
                                            Terlambat
                                        @elseif($presence->status === 'absent')
                                            Tidak Hadir
                                        @else
                                            {{ ucfirst($presence->status) }}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data kehadiran ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $presences->links() }}
        </div>
    </div>
</div>
@endsection 