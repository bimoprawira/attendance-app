<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-64 whitespace-nowrap">Info Karyawan</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Jabatan</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Kuota Cuti</th>
            <th class="px-4 py-3 text-left table-header font-medium text-gray-500 uppercase tracking-wider w-32 whitespace-nowrap">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach($employees as $employee)
        <tr class="hover:bg-gray-50 transition-colors duration-200">
            <td class="px-4 py-3 table-cell">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                        <span class="text-indigo-600 font-medium text-lg">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                    </div>
                    <div class="ml-4">
                        <div class="text-base font-medium text-gray-900">{!! highlight($employee->name, $searchTerm ?? '') !!}</div>
                        <div class="text-sm text-gray-500">{!! highlight($employee->email, $searchTerm ?? '') !!}</div>
                        <div class="text-xs text-gray-400">Bergabung pada {{ $employee->date_joined->format('M d, Y') }}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 table-cell">
                <span class="px-3 py-1 text-base text-indigo-600 bg-indigo-100 rounded-full">
                    {{ $employee->position }}
                </span>
            </td>
            <td class="px-4 py-3 table-cell">
                <div class="space-y-1">
                    <div class="flex items-center text-base">
                        <span class="w-20 text-gray-500">Tahunan:</span>
                        <div class="ml-2 flex items-center">
                            <span class="text-green-600 font-medium">{{ $employee->annual_leave_quota - $employee->used_annual_leave }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $employee->annual_leave_quota }}</span>
                        </div>
                    </div>
                    <div class="flex items-center text-base">
                        <span class="w-20 text-gray-500">Sakit:</span>
                        <div class="ml-2 flex items-center">
                            <span class="text-green-600 font-medium">{{ $employee->sick_leave_quota - $employee->used_sick_leave }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $employee->sick_leave_quota }}</span>
                        </div>
                    </div>
                    <div class="flex items-center text-base">
                        <span class="w-20 text-gray-500">Darurat:</span>
                        <div class="ml-2 flex items-center">
                            <span class="text-green-600 font-medium">{{ $employee->emergency_leave_quota - $employee->used_emergency_leave }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="text-gray-600">{{ $employee->emergency_leave_quota }}</span>
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-base text-gray-500 table-cell">
                <div class="flex space-x-3">
                    <button onclick="editEmployee({{ $employee->employee_id }}, '{{ $employee->name }}', '{{ $employee->email }}', '{{ $employee->position }}', '{{ $employee->date_joined->format('Y-m-d') }}', {{ $employee->annual_leave_quota }}, {{ $employee->sick_leave_quota }}, {{ $employee->emergency_leave_quota }})"
                            class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out">
                        Edit
                    </button>
                    <button onclick="deleteEmployee({{ $employee->employee_id }})"
                            class="px-4 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg font-medium transition duration-150 ease-in-out">
                        Hapus
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-8">
    {{ $employees->links() }} 