@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Leave Management</h2>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Leave Quotas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 mb-2">Annual Leave</h3>
                    <p class="text-blue-600">
                        Available: {{ Auth::user()->annual_leave_quota - Auth::user()->used_annual_leave }} days
                    </p>
                    <p class="text-sm text-blue-500">
                        Used: {{ Auth::user()->used_annual_leave }} / {{ Auth::user()->annual_leave_quota }}
                    </p>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 mb-2">Sick Leave</h3>
                    <p class="text-green-600">
                        Available: {{ Auth::user()->sick_leave_quota - Auth::user()->used_sick_leave }} days
                    </p>
                    <p class="text-sm text-green-500">
                        Used: {{ Auth::user()->used_sick_leave }} / {{ Auth::user()->sick_leave_quota }}
                    </p>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-purple-800 mb-2">Emergency Leave</h3>
                    <p class="text-purple-600">
                        Available: {{ Auth::user()->emergency_leave_quota - Auth::user()->used_emergency_leave }} days
                    </p>
                    <p class="text-sm text-purple-500">
                        Used: {{ Auth::user()->used_emergency_leave }} / {{ Auth::user()->emergency_leave_quota }}
                    </p>
                </div>
            </div>

            <!-- Request Leave Button -->
            <div class="flex justify-end mb-6">
                <a href="{{ route('leaves.create') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Request Leave
                </a>
            </div>

            <!-- Leave Requests Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($leaves as $leave)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="capitalize">{{ $leave->type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($leave->status === 'approved') bg-green-100 text-green-800
                                        @elseif($leave->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick="showLeaveDetails({{ $leave->leave_id }})" 
                                            class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Leave Details Modal -->
<div id="leaveDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Leave Request Details</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-1">
                    <strong>Type:</strong> <span id="leaveType"></span>
                </p>
                <p class="text-sm text-gray-500 mb-1">
                    <strong>From:</strong> <span id="leaveStart"></span>
                </p>
                <p class="text-sm text-gray-500 mb-1">
                    <strong>To:</strong> <span id="leaveEnd"></span>
                </p>
                <p class="text-sm text-gray-500 mb-1">
                    <strong>Status:</strong> <span id="leaveStatus"></span>
                </p>
                <p class="text-sm text-gray-500 mb-1">
                    <strong>Reason:</strong>
                    <span id="leaveReason" class="block mt-1"></span>
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showLeaveDetails(leaveId) {
        console.log('Showing details for leave:', leaveId);
        fetch(`/leaves/${leaveId}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                document.getElementById('leaveType').textContent = data.type;
                document.getElementById('leaveStart').textContent = data.start_date;
                document.getElementById('leaveEnd').textContent = data.end_date;
                document.getElementById('leaveStatus').textContent = data.status;
                document.getElementById('leaveReason').textContent = data.reason;
                document.getElementById('leaveDetailsModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load leave details. Please try again.');
            });
    }

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('leaveDetailsModal').classList.add('hidden');
    });

    // Close modal when clicking outside
    document.getElementById('leaveDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection 