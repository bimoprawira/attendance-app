@extends('layouts.app')

@section('title', 'My Payroll')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">My Salary History</h2>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($gajis->count() > 0)
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Gaji Pokok</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tambahan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Potongan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Gaji</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($gajis as $gaji)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $gaji->periode_bayar }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">Rp{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">Rp{{ number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">Rp{{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-sm font-semibold text-gray-900">
                            Rp{{ number_format($gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('gaji.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded">
                Download Excel
            </a>
        </div>
    @else
        <p class="text-gray-500">Belum ada data gaji tersedia.</p>
    @endif
</div>
@endsection
