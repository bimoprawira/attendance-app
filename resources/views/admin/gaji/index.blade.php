@extends('layouts.app')

@section('title', 'Manajemen Gaji')

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
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <div class="mb-8">
            <h2 class="section-title font-bold text-indigo-700">Manajemen Gaji</h2>
        </div>
        <div class="flex gap-2 mb-4">
            <div class="w-72 bg-white rounded-xl shadow-lg p-4">
                <label for="filter_month" class="block text-base font-medium text-gray-700 mb-2">Periode</label>
                <input type="month" id="filter_month" name="month" value="{{ $month }}" class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base" onchange="resetPageAndSubmit()" form="gajiFilterForm">
            </div>
        </div>
        <form id="gajiFilterForm" method="GET" class="hidden">
            <input type="hidden" name="page" id="filter_page" value="1">
        </form>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($employees->isEmpty())
            {{-- No message or content if empty --}}
        @else
        <div class="bg-white shadow-lg rounded-2xl p-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Info Karyawan</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Gaji Pokok</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Status Slip Gaji</th>
                            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($employees as $employee)
                            @php
                                $defaultData = [
                                    'hadir' => 0,
                                    'telat' => 0,
                                    'absen' => 0,
                                    'on_leave' => 0,
                                    'libur' => 0,
                                    'not_checked_in' => 0,
                                    'days_worked' => 0,
                                    'absent_days' => 0,
                                    'workdays' => 0,
                                    'total_days' => 0,
                                ];
                                $data = array_merge($defaultData, $attendanceData[$employee->employee_id] ?? []);
                                $currentMonthSlip = $employee->gajis->first(function($gaji) use ($month) {
                                    return $gaji->periode_bayar === $month;
                                });
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 table-cell">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-medium text-lg">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-base font-medium text-gray-900">{{ $employee->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 table-cell">
                                    Rp{{ number_format($employee->gaji_pokok, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 table-cell">
                                    @php
                                        $status = $currentMonthSlip ? $currentMonthSlip->status : 'pending';
                                    @endphp
                                    <span class="px-3 py-1 text-sm rounded-full
                                        @if($status === 'approved' || $status === 'selesai') bg-green-100 text-green-800
                                        @elseif($status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 table-cell">
                                    <div class="flex space-x-2">
                                        @if(!$currentMonthSlip)
                                        <button onclick="openSlipGajiModal({{ $employee->employee_id }}, '{{ $employee->name }}', {{ $employee->gaji_pokok }}, {{ $data['workdays'] }}, {{ $data['absent_days'] }}, {{ $data['hadir'] }}, {{ $data['telat'] }}, {{ $data['absen'] }})"
                                            class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out">
                                            Kelola Slip
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-8">
            {{ $employees->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Slip Gaji Modal -->
<div id="slipGajiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative mx-auto mt-32 mb-10 p-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center px-8 pt-8 pb-2">
            <h3 class="text-xl leading-6 font-bold text-indigo-700">Kelola Slip Gaji</h3>
            <button type="button" onclick="closeSlipGajiModal()" class="text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
        </div>
        <form id="slipGajiForm" method="POST" class="space-y-4 px-8 pb-8 pt-2">
            @csrf
            <input type="hidden" name="employee_id" id="slip_employee_id">
            <input type="hidden" name="periode_bayar" id="slip_periode_bayar" value="{{ $month }}">
            <div class="mb-4">
                <label class="block text-gray-700 text-base font-semibold mb-1">Nama Karyawan</label>
                <div id="slip_employee_name" class="text-lg font-medium text-gray-900"></div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-base font-semibold mb-1">Gaji Pokok</label>
                <div class="flex items-center">
                    <span class="inline-block mr-2 text-gray-500 font-semibold">Rp</span>
                    <span id="slip_gaji_pokok" class="text-lg font-medium text-gray-900"></span>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-base font-semibold mb-1">Potongan (Otomatis)</label>
                <div class="flex items-center">
                    <span class="inline-block mr-2 text-gray-500 font-semibold">Rp</span>
                    <input type="number" name="potongan" id="slip_potongan" readonly
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base font-semibold">
                </div>
                <div class="text-xs text-gray-500 mt-1">Potongan = (Gaji Pokok / Hari Kerja) × Hari Absen</div>
                <div id="slip_attendance_details" class="text-sm text-gray-600 mt-2 mb-2">
                    <div class="font-semibold">Kehadiran bulan ini:</div>
                    <div>Hadir: <span id="slip_hadir_count">0</span></div>
                    <div>Telat: <span id="slip_telat_count">0</span></div>
                    <div>Tidak Hadir: <span id="slip_absen_count">0</span></div>
                </div>
            </div>
            <div class="mb-4">
                <label for="komponen_tambahan" class="block text-gray-700 text-base font-semibold mb-1">Tambahan</label>
                <div class="flex items-center">
                    <span class="inline-block mr-2 text-gray-500 font-semibold">Rp</span>
                    <input type="number" name="komponen_tambahan" id="komponen_tambahan" value="0"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-8">
                <button type="button" onclick="closeSlipGajiModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan & Cetak Slip
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSlipGajiModal(employeeId, employeeName, gajiPokok, workdays, absentDays, hadir, telat, absen) {
    document.getElementById('slipGajiModal').classList.remove('hidden');
    document.getElementById('slip_employee_id').value = employeeId;
    document.getElementById('slip_employee_name').textContent = employeeName;
    document.getElementById('slip_gaji_pokok').textContent = parseInt(gajiPokok).toLocaleString('id-ID');
    // Use passed attendance data
    let potongan = 0;
    if (workdays > 0) {
        potongan = Math.round((gajiPokok / workdays) * absentDays);
    }
    document.getElementById('slip_potongan').value = potongan;
    document.getElementById('komponen_tambahan').value = 0;
    document.getElementById('slip_hadir_count').textContent = hadir;
    document.getElementById('slip_telat_count').textContent = telat;
    document.getElementById('slip_absen_count').textContent = absen;
    document.getElementById('slipGajiForm').action = `/admin/gaji/${employeeId}/slip`;
    document.getElementById('slip_periode_bayar').value = "{{ $month }}";
}

function closeSlipGajiModal() {
    document.getElementById('slipGajiModal').classList.add('hidden');
}

function resetPageAndSubmit() {
    document.getElementById('filter_page').value = 1;
    document.getElementById('gajiFilterForm').submit();
}
</script>
@endsection
