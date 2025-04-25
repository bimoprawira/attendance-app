@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Dashboard</h2>

        <!-- Employees Attendance Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Employees Attendance</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div class="bg-white rounded-lg shadow-md p-5">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Total Employees</h4>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalEmployees }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Present Today</h4>
                    <p class="text-3xl font-bold text-green-600">{{ $todayPresent }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Late Today</h4>
                    <p class="text-3xl font-bold text-yellow-600">{{ $todayLate }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">Absent Today</h4>
                    <p class="text-3xl font-bold text-red-600">{{ $todayAbsent }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5">
                    <h4 class="text-sm font-semibold text-gray-600 mb-2">On Leave</h4>
                    <p class="text-3xl font-bold text-purple-600">{{ $todayOnLeave }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Attendances -->
        <div class="bg-white rounded-lg shadow-md p-5 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Recent Attendances</h3>
                <a href="{{ route('admin.attendance.index') }}" class="text-blue-500 hover:text-blue-700">View All</a>
            </div>

            @if($recentAttendances->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAttendances as $attendance)
                                <tr>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">
                                                    {{ strtoupper(substr($attendance->employee->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->employee->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $attendance->employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($attendance->status === 'present') bg-green-100 text-green-800
                                            @elseif($attendance->status === 'late') bg-yellow-100 text-yellow-800
                                            @elseif($attendance->status === 'on_leave' || (isset($attendance->is_on_leave) && $attendance->is_on_leave)) bg-purple-100 text-purple-800
                                            @elseif($attendance->status === 'not_checked_in') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($attendance->status === 'on_leave' || (isset($attendance->is_on_leave) && $attendance->is_on_leave))
                                                On Leave
                                            @elseif($attendance->status === 'not_checked_in')
                                                Not Checked In
                                            @else
                                                {{ ucfirst($attendance->status) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No recent attendances.</p>
            @endif
        </div>

        <!-- Leave Requests Section -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Leave Requests</h3>
            <div class="bg-white rounded-lg shadow-md p-5">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-1">Pending Leaves</h4>
                        <p class="text-3xl font-bold text-yellow-600">{{ $pendingLeaves }}</p>
                    </div>
                    <a href="{{ route('admin.leaves.index') }}" class="text-blue-500 hover:text-blue-700">View All</a>
                </div>
            </div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="bg-white rounded-lg shadow-md p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Recent Leave Requests</h3>
                <a href="{{ route('admin.leaves.index') }}" class="text-blue-500 hover:text-blue-700">View All</a>
            </div>

            @if($recentLeaves->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentLeaves as $leave)
                                <tr>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $leave->employee->email }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst($leave->type) }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $leave->duration }} days
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($leave->status === 'approved') bg-green-100 text-green-800
                                            @elseif($leave->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No recent leave requests.</p>
            @endif
        </div>
    </div>
</div>
@endsection
