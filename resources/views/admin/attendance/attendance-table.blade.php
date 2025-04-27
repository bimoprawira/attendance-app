<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-4 text-left table-header font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
            <th class="px-6 py-4 text-left table-header font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-4 text-left table-header font-medium text-gray-500 uppercase tracking-wider">Absen Masuk</th>
            <th class="px-6 py-4 text-left table-header font-medium text-gray-500 uppercase tracking-wider">Absen Keluar</th>
            <th class="px-6 py-4 text-left table-header font-medium text-gray-500 uppercase tracking-wider">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($attendances as $attendance)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap table-cell">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-medium text-lg">
                                    {{ strtoupper(substr($attendance->employee->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-medium text-gray-900">{{ $attendance->employee->name }}</div>
                            <div class="text-sm text-gray-500">{{ $attendance->employee->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap table-cell text-gray-500">
                    {{ $attendance->date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap table-cell text-gray-500">
                    {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap table-cell text-gray-500">
                    {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap table-cell">
                    @if(isset($attendance->is_on_leave) && $attendance->is_on_leave)
                        <span class="px-3 py-1 inline-flex text-sm leading-6 font-semibold rounded-full bg-purple-100 text-purple-800">
                            Cuti
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($attendance->status === 'present') bg-green-100 text-green-800
                            @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                            @elseif($attendance->status === 'on_leave') bg-purple-100 text-purple-800
                            @elseif($attendance->status === 'not_checked_in') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($attendance->status === 'present')
                                Hadir
                            @elseif($attendance->status === 'late')
                                Terlambat
                            @elseif($attendance->status === 'on_leave')
                                Cuti
                            @elseif($attendance->status === 'not_checked_in')
                                Belum Absen Masuk
                            @elseif($attendance->status === 'absent')
                                Tidak Hadir
                            @else
                                {{ ucfirst($attendance->status) }}
                            @endif
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table> 