@props(['leaves'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-64 whitespace-nowrap">Karyawan</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Jenis Cuti</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Durasi</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-24 whitespace-nowrap">Status</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-48 whitespace-nowrap">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200 text-sm">
        @forelse($leaves as $leave)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 table-cell whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-medium text-lg">
                                    {{ strtoupper(substr($leave->employee->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 whitespace-nowrap">{{ $leave->employee->name }}</div>
                            <div class="text-sm text-gray-500 whitespace-nowrap">{{ $leave->employee->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 table-cell whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                        @if($leave->type === 'annual') bg-blue-100 text-blue-800
                        @elseif($leave->type === 'sick') bg-green-100 text-green-800
                        @elseif($leave->type === 'emergency') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif whitespace-nowrap">
                        {{ $leave->type === 'annual' ? 'Cuti Tahunan' : ($leave->type === 'sick' ? 'Cuti Sakit' : ($leave->type === 'emergency' ? 'Cuti Darurat' : ucfirst($leave->type))) }}
                    </span>
                </td>
                <td class="px-4 py-3 table-cell text-gray-500 whitespace-nowrap">
                    {{ $leave->duration }} hari
                </td>
                <td class="px-4 py-3 table-cell whitespace-nowrap">
                    <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full
                        {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($leave->status === 'rejected' || $leave->status === 'declined' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800') }} whitespace-nowrap">
                        @if($leave->status === 'pending')
                            Menunggu Persetujuan
                        @elseif($leave->status === 'approved')
                            Disetujui
                        @elseif($leave->status === 'rejected' || $leave->status === 'declined')
                            Ditolak
                        @else
                            {{ ucfirst($leave->status) }}
                        @endif
                    </span>
                </td>
                <td class="px-4 py-3 table-cell font-medium whitespace-nowrap">
                    <div class="flex gap-2">
                        <button type="button" onclick="showLeaveDetails({{ $leave->leave_id ?? $leave->id ?? 0 }}, '{{ $leave->type }}', '{{ $leave->start_date->format('Y-m-d') }}', '{{ $leave->end_date->format('Y-m-d') }}', '{{ $leave->status }}', `{{ addslashes($leave->reason ?? '') }}`, '{{ $leave->employee->name }}', '{{ $leave->duration }}')" class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                            Detail
                        </button>
                        @if($leave->status === 'pending')
                            <form method="POST" action="{{ route('admin.leaves.approve', $leave->leave_id ?? $leave->id ?? 0) }}">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-1.5 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.leaves.reject', $leave->leave_id ?? $leave->id ?? 0) }}">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg font-medium transition duration-150 ease-in-out whitespace-nowrap">
                                    Tolak
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-3 table-cell text-center text-gray-500 whitespace-nowrap">
                    Belum ada daftar permintaan cuti.
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 