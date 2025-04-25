@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Attendance</h2>

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

            <!-- Attendance Rules -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-6">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                    <h3 class="text-lg font-semibold text-gray-800">Attendance Rules</h3>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Form Opens -->
                    <div class="flex items-center">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500">Form Opens:</span>
                        </div>
                        <div class="flex-grow">
                            <span class="text-sm font-semibold text-blue-600">{{ $formOpenTime }}</span>
                        </div>
                    </div>

                    <!-- Present -->
                    <div class="flex items-center">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500">Present:</span>
                        </div>
                        <div class="flex-grow">
                            <span class="text-sm font-semibold text-green-600">Check-in before {{ $presentBefore }}</span>
                        </div>
                    </div>

                    <!-- Late -->
                    <div class="flex items-center">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500">Late:</span>
                        </div>
                        <div class="flex-grow">
                            <span class="text-sm font-semibold text-yellow-600">Check-in between {{ $presentBefore }} and {{ $lateBefore }}</span>
                        </div>
                    </div>

                    <!-- Absent -->
                    <div class="flex items-center">
                        <div class="w-32 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-500">Absent:</span>
                        </div>
                        <div class="flex-grow">
                            <span class="text-sm font-semibold text-red-600">No check-in by {{ $lateBefore }}</span>
                        </div>
                    </div>

                    <!-- Current Time -->
                    <div class="flex flex-col pt-4 mt-2 border-t border-gray-200">
                        <div class="mb-2">
                            <span class="text-sm font-medium text-gray-500">Current Time</span>
                        </div>
                        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-3 shadow-sm">
                            <div id="currentTime" class="text-center">
                                <span id="timeDigits" class="font-mono text-3xl font-bold text-indigo-600"></span>
                                <span id="timeAmPm" class="text-sm font-semibold text-indigo-500 ml-2"></span>
                            </div>
                            <div class="text-center mt-1">
                                <span id="timeDate" class="text-xs text-gray-500"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Today's Status</h3>
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Current Status:</p>
                            <p class="font-medium mt-1">
                                @if($status === 'present')
                                    <span class="text-green-600">Present</span>
                                @elseif($status === 'late')
                                    <span class="text-yellow-600">Late</span>
                                @elseif($status === 'absent')
                                    <span class="text-red-600">Absent</span>
                                @elseif($status === 'on_leave')
                                    <span class="text-purple-600">On Leave</span>
                                @else
                                    @if(now()->format('H:i') > $lateBefore)
                                        <span class="text-red-600">Absent</span>
                                    @else
                                        <span class="text-gray-600">Not Checked In</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                        <div>
                            @if($presence)
                                @if($presence->check_in)
                                    <p class="text-sm text-gray-600">Check-in time: {{ optional($presence->check_in)->format('H:i') ?? '-' }}</p>
                                @endif
                                @if($presence->check_out)
                                    <p class="text-sm text-gray-600">Check-out time: {{ optional($presence->check_out)->format('H:i') ?? '-' }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @php
                $currentTime = \Carbon\Carbon::now();
                $lateThreshold = \Carbon\Carbon::createFromTimeString($lateBefore);
                $isPastLateThreshold = $currentTime->gt($lateThreshold);
                $formOpenTime = \Carbon\Carbon::createFromTimeString($formOpenTime);
                $isFormOpen = $currentTime->gte($formOpenTime);
            @endphp

            <div class="flex justify-center items-center space-x-4 mb-8">
                @if($status === 'on_leave')
                    <p class="text-blue-500 italic">You are on leave today.</p>
                @elseif($status === 'absent')
                    <p class="text-red-500 italic">Check-in is no longer available. You are marked as absent for today.</p>
                @elseif($isPastLateThreshold && !$presence->check_in)
                    <p class="text-red-500 italic">Check-in is no longer available. You are marked as absent for today.</p>
                @elseif(!$presence->check_in)
                    @if(!$isFormOpen)
                        <p class="text-blue-500 italic">Check-in will be available from {{ $formOpenTime }}</p>
                    @else
                        <form action="{{ route('presence.checkIn') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Check In
                            </button>
                        </form>
                    @endif
                @elseif(!$presence->check_out && in_array($status, ['present', 'late']))
                    <form action="{{ route('presence.checkOut') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Check Out
                        </button>
                    </form>
                @else
                    <p class="text-gray-500 italic">You have completed your attendance for today</p>
                @endif
            </div>

            <div class="mt-8">
                <a href="{{ route('presence.history') }}"
                   class="text-blue-500 hover:text-blue-600 font-medium">
                    View Attendance History →
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateClock() {
    const now = new Date();

    // Format time
    const hours = now.getHours();
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const displayHours = String(hours % 12 || 12).padStart(2, '0');

    // Format date
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const day = days[now.getDay()];
    const date = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();

    // Update DOM
    document.getElementById('timeDigits').textContent = `${displayHours}:${minutes}:${seconds}`;
    document.getElementById('timeAmPm').textContent = ampm;
    document.getElementById('timeDate').textContent = `${day}, ${month} ${date}, ${year}`;
}

// Update clock immediately and then every second
updateClock();
setInterval(updateClock, 1000);
</script>

@endsection
