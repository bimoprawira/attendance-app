@props(['employees', 'presences', 'date', 'status'])

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
        @php
            $search = isset($search) ? $search : request('search');
            $highlight = function($text) use ($search) {
                if (!$search) return e($text);
                return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200">$1</mark>', e($text));
            };
        @endphp
        @foreach($employees as $employee)
            @php
                $presence = $presences[$employee->employee_id] ?? null;
                $show = true;
                if ($status && $presence) {
                    $show = $presence->status === $status;
                } elseif ($status && !$presence) {
                    $show = $status === 'absent';
                }
            @endphp
            @if($show)
            <tr>
                <td class="px-4 py-3 whitespace-nowrap table-cell">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-medium text-lg">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-gray-900">{!! $highlight($employee->name) !!}</div>
                            <div class="text-gray-500">{!! $highlight($employee->email) !!}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ $presence && $presence->check_in ? $presence->check_in->format('H:i') : '-' }}</td>
                <td class="px-4 py-3 text-gray-500 table-cell">{{ $presence && $presence->check_out ? $presence->check_out->format('H:i') : '-' }}</td>
                <td class="px-4 py-3 table-cell">
                    @php
                        $statusVal = $presence ? $presence->status : 'absent';
                    @endphp
                    <span class="px-2 inline-flex text-base leading-6 font-semibold rounded-full
                        @if($statusVal === 'libur') bg-blue-100 text-blue-800
                        @elseif($statusVal === 'present') bg-green-100 text-green-800
                        @elseif($statusVal === 'late') bg-yellow-100 text-yellow-800
                        @elseif($statusVal === 'on_leave') bg-purple-100 text-purple-800
                        @elseif($statusVal === 'not_checked_in') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800 @endif">
                        @if($statusVal === 'libur')
                            Libur
                        @elseif($statusVal === 'on_leave')
                            Cuti
                        @elseif($statusVal === 'not_checked_in')
                            Belum Presensi
                        @elseif($statusVal === 'present')
                            Hadir
                        @elseif($statusVal === 'late')
                            Terlambat
                        @elseif($statusVal === 'absent')
                            Tidak Hadir
                        @else
                            {{ ucfirst($statusVal) }}
                        @endif
                    </span>
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table> 
@if(method_exists($employees, 'links'))
<div class="mt-8">
    {{ $employees->appends(request()->except('page'))->links() }}
</div>
@endif 