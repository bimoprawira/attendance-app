@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Employee Dashboard</h2>
                <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Attendance Card -->
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">Attendance</h3>
                    <div class="space-y-4">
                        <a href="{{ route('presence.index') }}" 
                           class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                            Check Attendance
                        </a>
                        <a href="{{ route('presence.history') }}" 
                           class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded text-center">
                            View History
                        </a>
                    </div>
                </div>

                <!-- Leave Management Card -->
                <div class="bg-green-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-green-800 mb-4">Leave Management</h3>
                    <div class="space-y-4">
                        <a href="{{ route('leaves.create') }}" 
                           class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded text-center">
                            Request Leave
                        </a>
                        <a href="{{ route('leaves.index') }}" 
                           class="block w-full bg-green-100 hover:bg-green-200 text-green-700 font-medium py-2 px-4 rounded text-center">
                            View Requests
                        </a>
                    </div>
                </div>

                <!-- Profile Card -->
                <div class="bg-purple-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-purple-800 mb-4">Profile Information</h3>
                    <div class="space-y-2">
                        <p class="text-gray-600"><span class="font-medium">Position:</span> {{ Auth::user()->position }}</p>
                        <p class="text-gray-600"><span class="font-medium">Date Joined:</span> {{ Auth::user()->date_joined->format('Y-m-d') }}</p>
                        <p class="text-gray-600"><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 