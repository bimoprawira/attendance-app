@extends('layouts.app')

@section('title', 'Leave Request Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Leave Requests</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Duration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Dates</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-lg">
                                                {{ strtoupper(substr($leave->employee->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $leave->employee->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $leave->employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($leave->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $leave->duration }} days
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900 whitespace-pre-line">{{ $leave->reason }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' :
                                       ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm font-medium">
                                @if($leave->status === 'pending')
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('admin.leaves.approve', $leave->leave_id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-1.5 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg font-medium transition duration-150 ease-in-out">
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.leaves.reject', $leave->leave_id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="px-4 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg font-medium transition duration-150 ease-in-out">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-500">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                No leave requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $leaves->links() }}
        </div>
    </div>
</div>
@endsection
