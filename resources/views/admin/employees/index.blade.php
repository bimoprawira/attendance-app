@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Manajemen Karyawan</h2>

    <div class="flex justify-end mb-6">
        <button onclick="openAddEmployeeModal()"
                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg inline-flex items-center transition duration-150 ease-in-out">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Karyawan
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota Cuti</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-medium text-lg">{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                    <div class="text-xs text-gray-400">Bergabung pada {{ $employee->date_joined->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-sm text-blue-600 bg-blue-100 rounded-full">
                                {{ $employee->position }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center text-sm">
                                    <span class="w-20 text-gray-500">Cuti Tahunan:</span>
                                    <div class="ml-2 flex items-center">
                                        <span class="text-green-600 font-medium">{{ $employee->annual_leave_quota - $employee->used_annual_leave }}</span>
                                        <span class="text-gray-400 mx-1">/</span>
                                        <span class="text-gray-600">{{ $employee->annual_leave_quota }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="w-20 text-gray-500">Sakit:</span>
                                    <div class="ml-2 flex items-center">
                                        <span class="text-green-600 font-medium">{{ $employee->sick_leave_quota - $employee->used_sick_leave }}</span>
                                        <span class="text-gray-400 mx-1">/</span>
                                        <span class="text-gray-600">{{ $employee->sick_leave_quota }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="w-20 text-gray-500">Darurat:</span>
                                    <div class="ml-2 flex items-center">
                                        <span class="text-green-600 font-medium">{{ $employee->emergency_leave_quota - $employee->used_emergency_leave }}</span>
                                        <span class="text-gray-400 mx-1">/</span>
                                        <span class="text-gray-600">{{ $employee->emergency_leave_quota }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-3">
                                <button onclick="editEmployee({{ $employee->employee_id }}, '{{ $employee->name }}', '{{ $employee->email }}', '{{ $employee->position }}', '{{ $employee->date_joined->format('Y-m-d') }}', {{ $employee->annual_leave_quota }}, {{ $employee->sick_leave_quota }}, {{ $employee->emergency_leave_quota }})"
                                        class="px-4 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg font-medium transition duration-150 ease-in-out">
                                    Sunting
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
        </div>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Karyawan Baru</h3>
            <form method="POST" action="{{ route('admin.employees.store') }}" class="mt-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nama
                    </label>
                    <input type="text" name="name" id="name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input type="email" name="email" id="email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="position">
                        Jabatan
                    </label>
                    <input type="text" name="position" id="position" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date_joined">
                        Tanggal Bergabung
                    </label>
                    <input type="date" name="date_joined" id="date_joined" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Kata Sandi
                    </label>
                    <input type="password" name="password" id="password" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Cuti</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="annual_leave_quota">Cuti Tahunan</label>
                            <input type="number" name="annual_leave_quota" id="annual_leave_quota" required min="0" value="12"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="sick_leave_quota">Sakit</label>
                            <input type="number" name="sick_leave_quota" id="sick_leave_quota" required min="0" value="12"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="emergency_leave_quota">Darurat</label>
                            <input type="number" name="emergency_leave_quota" id="emergency_leave_quota" required min="0" value="6"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Tambah Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Sunting Data Karyawan</h3>
            <form id="editEmployeeForm" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_name">
                        Nama
                    </label>
                    <input type="text" name="name" id="edit_name" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_email">
                        Email
                    </label>
                    <input type="email" name="email" id="edit_email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_position">
                        Jabatan
                    </label>
                    <input type="text" name="position" id="edit_position" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_date_joined">
                        Tanggal Bergabung
                    </label>
                    <input type="date" name="date_joined" id="edit_date_joined" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="edit_password">
                        Kata Sandi (kosongkan jika tidak ingin diubah)
                    </label>
                    <input type="password" name="password" id="edit_password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kuota Cuti</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_annual_leave_quota">Cuti Tahunan</label>
                            <input type="number" name="annual_leave_quota" id="edit_annual_leave_quota" required min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_sick_leave_quota">Sakit</label>
                            <input type="number" name="sick_leave_quota" id="edit_sick_leave_quota" required min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_emergency_leave_quota">Darurat</label>
                            <input type="number" name="emergency_leave_quota" id="edit_emergency_leave_quota" required min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddEmployeeModal() {
    document.getElementById('addEmployeeModal').classList.remove('hidden');
}

function editEmployee(id, name, email, position, dateJoined, annualQuota, sickQuota, emergencyQuota) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_position').value = position;
    document.getElementById('edit_date_joined').value = dateJoined;
    document.getElementById('editEmployeeForm').action = `/admin/employees/${id}`;
    document.getElementById('editEmployeeModal').classList.remove('hidden');
}

function deleteEmployee(id) {
    if (confirm('Are you sure you want to delete this employee?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/employees/${id}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection
