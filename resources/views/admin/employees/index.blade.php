@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    .section-title { font-size: 1.7rem; }
    .table-header { font-size: 1rem; }
    .table-cell { font-size: 1rem; }
</style>
<div class="flex justify-center items-start min-h-screen py-8">
    <div class="w-full max-w-7xl bg-white/90 rounded-3xl shadow-2xl p-10">
        <div class="flex justify-between items-center mb-8">
            <h2 class="section-title font-bold text-indigo-700">Manajemen Karyawan</h2>
            <button onclick="openAddEmployeeModal()"
                class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-xl shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Karyawan
            </button>
        </div>
        <!-- Search Bar -->
        <form method="GET" class="mb-4">
            <div class="bg-white rounded-xl shadow-lg p-4 w-full max-w-md">
                <label for="search" class="block text-base font-medium text-gray-700 mb-2">Cari Karyawan</label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Masukkan nama atau email karyawan"
                           class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base pr-10">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden p-8">
            <div class="overflow-x-auto" id="employees-table-area">
                @include('admin.employees._table', ['employees' => $employees, 'searchTerm' => request('search', '')])
            </div>
        </div>
        <div class="mt-8">
            {{ $employees->links() }}
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative mx-auto mt-32 mb-10 p-0 w-full max-w-2xl shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center px-8 pt-8 pb-2">
            <h3 class="text-xl leading-6 font-bold text-indigo-700">Tambah Karyawan Baru</h3>
            <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
        </div>
        <form id="addEmployeeForm" method="POST" action="{{ route('admin.employees.store') }}" class="space-y-4 px-8 pb-8 pt-2">
            @csrf
            <div class="error-messages mb-2"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="name">Nama</label>
                    <input type="text" name="name" id="name" required placeholder="Nama lengkap"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="email">Email</label>
                    <input type="email" name="email" id="email" required placeholder="email@perusahaan.com"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="position">Jabatan</label>
                    <input type="text" name="position" id="position" required placeholder="Jabatan (misal: Staff, Manager)"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="password">Password</label>
                    <input type="password" name="password" id="password" required placeholder="Password minimal 8 karakter"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="date_joined">Tanggal Bergabung</label>
                    <input type="date" name="date_joined" id="date_joined" required
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="gaji_pokok">Gaji Pokok</label>
                    <div class="flex items-center">
                        <span class="inline-block mr-2 text-gray-500 font-semibold">Rp</span>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" required placeholder="Gaji Pokok"
                            class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1">Kuota Cuti</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="annual_leave_quota">Cuti Tahunan</label>
                            <input type="number" name="annual_leave_quota" id="annual_leave_quota" required min="0" value="12"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="sick_leave_quota">Sakit</label>
                            <input type="number" name="sick_leave_quota" id="sick_leave_quota" required min="0" value="12"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="emergency_leave_quota">Darurat</label>
                            <input type="number" name="emergency_leave_quota" id="emergency_leave_quota" required min="0" value="6"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 mt-10 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-xl">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow">
                        Tambah Karyawan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Employee Modal -->
<div id="editEmployeeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative mx-auto mt-32 mb-10 p-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white">
        <div class="flex justify-between items-center px-8 pt-8 pb-2">
            <h3 class="text-xl leading-6 font-bold text-indigo-700">Edit Data Karyawan</h3>
            <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 text-2xl font-bold focus:outline-none">&times;</button>
        </div>
        <form id="editEmployeeForm" method="POST" class="space-y-4 px-8 pb-8 pt-2">
            @csrf
            @method('PUT')
            <div class="error-messages mb-2"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_name">Nama</label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_email">Email</label>
                    <input type="email" name="email" id="edit_email" required
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_position">Jabatan</label>
                    <input type="text" name="position" id="edit_position" required
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_password">Password (Kosongkan bila tidak ingin dirubah)</label>
                    <input type="password" name="password" id="edit_password"
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_date_joined">Tanggal Bergabung</label>
                    <input type="date" name="date_joined" id="edit_date_joined" required
                        class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1" for="edit_gaji_pokok">Gaji Pokok</label>
                    <div class="flex items-center">
                        <span class="inline-block mr-2 text-gray-500 font-semibold">Rp</span>
                        <input type="number" name="gaji_pokok" id="edit_gaji_pokok" required placeholder="Gaji Pokok"
                            class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-3 text-base">
                    </div>
                </div>
                <div>
                    <label class="block text-gray-700 text-base font-semibold mb-1">Kuota Cuti</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_annual_leave_quota">Cuti Tahunan</label>
                            <input type="number" name="annual_leave_quota" id="edit_annual_leave_quota" required min="0"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_sick_leave_quota">Sakit</label>
                            <input type="number" name="sick_leave_quota" id="edit_sick_leave_quota" required min="0"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs mb-1" for="edit_emergency_leave_quota">Darurat</label>
                            <input type="number" name="emergency_leave_quota" id="edit_emergency_leave_quota" required min="0"
                                class="w-full rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 p-2 text-base">
                        </div>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2 mt-10 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-xl">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openAddEmployeeModal() {
    document.getElementById('addEmployeeModal').classList.remove('hidden');
}

function editEmployee(id, name, email, position, dateJoined, annualQuota, sickQuota, emergencyQuota, gajiPokok) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_position').value = position;
    document.getElementById('edit_date_joined').value = dateJoined;
    document.getElementById('edit_annual_leave_quota').value = annualQuota;
    document.getElementById('edit_sick_leave_quota').value = sickQuota;
    document.getElementById('edit_emergency_leave_quota').value = emergencyQuota;
    document.getElementById('edit_gaji_pokok').value = gajiPokok;
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

document.getElementById('search').addEventListener('input', function() {
    const search = this.value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    fetch(`/admin/employees/ajax-table?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('employees-table-area').innerHTML = html;
        });
});

function showFormErrors(form, errors) {
    const errorDiv = form.querySelector('.error-messages');
    if (!errorDiv) return;
    let html = '';
    Object.keys(errors).forEach(function(key) {
        errors[key].forEach(function(msg) {
            html += '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-2">• ' + msg + '</div>';
        });
    });
    errorDiv.innerHTML = html;
}

// Add Employee AJAX
const addEmployeeForm = document.getElementById('addEmployeeForm');
if (addEmployeeForm) {
    addEmployeeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(async res => {
            if (res.status === 422) {
                const data = await res.json();
                showFormErrors(form, data.errors);
            } else if (res.ok) {
                location.reload();
            }
        })
        .catch(() => {
            showFormErrors(form, {general: ['Terjadi kesalahan. Coba lagi.']});
        });
    });
}

// Edit Employee AJAX
const editEmployeeForm = document.getElementById('editEmployeeForm');
if (editEmployeeForm) {
    editEmployeeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const formData = new FormData(form);
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(async res => {
            if (res.status === 422) {
                const data = await res.json();
                showFormErrors(form, data.errors);
            } else if (res.ok) {
                location.reload();
            }
        })
        .catch(() => {
            showFormErrors(form, {general: ['Terjadi kesalahan. Coba lagi.']});
        });
    });
}
</script>

@endsection
