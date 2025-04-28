@extends('layouts.app')

@section('title', 'Daftar Gaji')

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
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="section-title font-bold text-indigo-700">Daftar Gaji</h2>
            <button onclick="openAddGajiModal()" type="button" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg text-base transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Gaji
            </button>
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($gajis->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Nama Karyawan</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Periode</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Gaji Pokok</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Tambahan</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Potongan</th>
                                <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Total Gaji</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($gajis as $gaji)
                            <tr>
                                <td class="px-4 py-3 table-cell text-gray-700">{{ $gaji->employee->name }}</td>
                                <td class="px-4 py-3 table-cell text-gray-700">{{ $gaji->periode_bayar }}</td>
                                <td class="px-4 py-3 table-cell text-gray-700">Rp{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 table-cell text-gray-700">Rp{{ number_format($gaji->komponen_tambahan ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 table-cell text-gray-700">Rp{{ number_format($gaji->potongan ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 table-cell font-semibold text-gray-900">
                                    Rp{{ number_format($gaji->gaji_pokok + ($gaji->komponen_tambahan ?? 0) - ($gaji->potongan ?? 0), 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.gaji.export') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2 rounded-lg text-base transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Unduh File Excel
                    </a>
                </div>
            @else
                <p class="text-gray-500 table-cell">Belum ada data gaji tersedia.</p>
            @endif
        </div>
    </div>
</div>

<!-- Add Gaji Modal -->
<div id="addGajiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative mx-auto mt-32 mb-10 p-4 w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="mt-3">
            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4">Tambah Gaji</h3>
            <form action="{{ route('admin.gaji.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="employee_id" class="block text-gray-700 text-base font-semibold mb-1">Karyawan</label>
                        <select name="employee_id" id="employee_id" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base" required>
                            @foreach($karyawans as $employee)
                                <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="periode_bayar" class="block text-gray-700 text-base font-semibold mb-1">Periode Bayar</label>
                        <input type="text" name="periode_bayar" id="periode_bayar" placeholder="Contoh: April 2025"
                               class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base" required>
                    </div>
                    <div>
                        <label for="gaji_pokok" class="block text-gray-700 text-base font-semibold mb-1">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok"
                               class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base" required>
                    </div>
                    <div>
                        <label for="potongan" class="block text-gray-700 text-base font-semibold mb-1">Potongan</label>
                        <input type="number" name="potongan" id="potongan"
                               class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                    </div>
                    <div>
                        <label for="komponen_tambahan" class="block text-gray-700 text-base font-semibold mb-1">Tambahan</label>
                        <input type="number" name="komponen_tambahan" id="komponen_tambahan"
                               class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 mt-10 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAddGajiModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-xl">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-wrapper.single .ts-control {
        padding: 12px;
        border-radius: 0.75rem;
        background-color: rgb(249 250 251);
        border-color: rgb(209 213 219);
    }
    .ts-dropdown {
        border-radius: 0.75rem;
        margin-top: 4px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    .ts-dropdown .active {
        background-color: rgb(238 242 255);
        color: rgb(79 70 229);
    }
    .ts-wrapper.single.input-active .ts-control {
        border-radius: 0.75rem;
        border-color: rgb(99 102 241);
        box-shadow: 0 0 0 1px rgb(99 102 241);
    }
</style>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
function openAddGajiModal() {
    document.getElementById('addGajiModal').classList.remove('hidden');
    // Initialize Tom Select when modal opens (if not already initialized)
    if (!window.employeeSelectInitialized) {
        new TomSelect('#employee_id', {
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
            placeholder: 'Ketik nama karyawan untuk mencari...',
            searchField: ['text'],
            maxOptions: null,
            persist: false,
            closeAfterSelect: true,
            plugins: ['clear_button'],
            render: {
                option: function(data, escape) {
                    return `<div class="py-2 px-3">
                        <div class="font-medium">${escape(data.text)}</div>
                    </div>`;
                },
                item: function(data, escape) {
                    return `<div>${escape(data.text)}</div>`;
                },
                no_results: function(data, escape) {
                    return '<div class="py-2 px-3 text-gray-600">Karyawan tidak ditemukan</div>';
                }
            }
        });
        window.employeeSelectInitialized = true;
    }
}
function closeAddGajiModal() {
    document.getElementById('addGajiModal').classList.add('hidden');
}
</script>
@endsection
