@props(['attendances'])

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-64 whitespace-nowrap">Karyawan</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Tanggal</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Presensi Masuk</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Presensi Keluar</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Status</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($attendances as $attendance)
            <tr>
                <td class="px-4 py-3 whitespace-nowrap table-cell">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-medium text-lg">
                                {{ strtoupper(substr($attendance->employee->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            @if(request('search'))
                                <div class="font-medium text-gray-900">{!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark class="bg-yellow-200">$1</mark>', e($attendance->employee->name)) !!}</div>
                                <div class="text-gray-500">{!! preg_replace('/('.preg_quote(request('search'), '/').')/i', '<mark class="bg-yellow-200">$1</mark>', e($attendance->employee->email)) !!}</div>
                            @else
                                <div class="font-medium text-gray-900">{{ $attendance->employee->name }}</div>
                                <div class="text-gray-500">{{ $attendance->employee->email }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ $attendance->date->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                <td class="px-4 py-3 table-cell">
                    <span class="px-2 inline-flex text-base leading-6 font-semibold rounded-full
                        @if($attendance->status === 'present') bg-green-100 text-green-800
                        @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                        @elseif($attendance->status === 'on_leave' || (isset($attendance->is_on_leave) && $attendance->is_on_leave)) bg-purple-100 text-purple-800
                        @elseif($attendance->status === 'not_checked_in') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800 @endif">
                        @if($attendance->status === 'on_leave' || (isset($attendance->is_on_leave) && $attendance->is_on_leave))
                            Cuti
                        @elseif($attendance->status === 'not_checked_in')
                            Belum Presensi
                        @elseif($attendance->status === 'present')
                            Hadir
                        @elseif($attendance->status === 'late')
                            Terlambat
                        @elseif($attendance->status === 'absent')
                            Tidak Hadir
                        @else
                            {{ ucfirst($attendance->status) }}
                        @endif
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table> 