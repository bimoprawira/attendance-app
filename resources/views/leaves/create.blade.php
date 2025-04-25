@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Request Leave</h2>
                <a href="{{ route('leaves.index') }}" 
                   class="text-blue-500 hover:text-blue-600">
                    ← Back to Leaves
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Leave Type</label>
                    <select name="type" id="type" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="emergency">Emergency Leave</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start_date" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>

                <div class="mb-4">
                    <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                    <input type="date" name="end_date" id="end_date" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           min="{{ date('Y-m-d') }}"
                           required>
                </div>

                <div class="mb-6">
                    <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Reason</label>
                    <textarea name="reason" id="reason" rows="4" 
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                              required></textarea>
                    <p class="mt-1 text-sm text-gray-500">Please provide a detailed reason for your leave request.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Ensure end date is not before start date
    document.getElementById('start_date').addEventListener('change', function() {
        document.getElementById('end_date').min = this.value;
    });
</script>
@endpush
@endsection 