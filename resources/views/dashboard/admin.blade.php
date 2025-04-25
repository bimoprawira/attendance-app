@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
        <div class="flex space-x-4">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'text-blue-500 hover:bg-blue-100' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.employees') }}"
               class="px-4 py-2 rounded {{ request()->routeIs('admin.employees') ? 'bg-blue-500 text-white' : 'text-blue-500 hover:bg-blue-100' }}">
                Employees
            </a>
            <a href="{{ route('admin.leaves.index') }}"
               class="px-4 py-2 rounded {{ request()->routeIs('admin.leaves.*') ? 'bg-blue-500 text-white' : 'text-blue-500 hover:bg-blue-100' }}">
                Leaves
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Employees</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalEmployees }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Present Today</h3>
            <p class="text-3xl font-bold text-green-600">{{ $todayPresent }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Late Today</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $todayLate }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Pending Leaves</h3>
            <p class="text-3xl font-bold text-red-600">{{ $pendingLeaves }}</p>
        </div>
    </div>

    <!-- Recent Leave Requests -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Recent Leave Requests</h3>
            <a href="{{ route('admin.leaves.index') }}" class="text-blue-500 hover:text-blue-700">View All</a>
        </div>

        @if($recentLeaves->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentLeaves as $leave)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $leave->employee->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $leave->employee->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $leave->duration }} days</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($leave->approval_status === 'approved') bg-green-100 text-green-800
                                        @elseif($leave->approval_status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($leave->approval_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($leave->approval_status === 'pending')
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave->leave_id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leaves.reject', $leave->leave_id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">{{ $leave->approval_date ? $leave->approval_date->format('M d, Y') : '-' }}</span>
                                    @endif
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
@endsection
