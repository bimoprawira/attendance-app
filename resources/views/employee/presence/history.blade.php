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
            <a href="{{ route('presence.index') }}" class="text-blue-500 hover:text-blue-600 font-medium">← Kembali ke Kehadiran</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($presences as $presence)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($presence->date)->format('Y-m-d') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($presence->check_in)->format('H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ optional($presence->check_out)->format('H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($presence->status === 'on_leave')
                                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-purple-100 text-purple-800">Cuti</span>
                                @elseif($presence->status === 'present')
                                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                @elseif($presence->status === 'late')
                                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                @elseif($presence->status === 'absent')
                                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-red-100 text-red-800">Absen</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Check-in</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada data kehadiran ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $presences->links() }}
        </div>
    </div>
</div>
@endsection 