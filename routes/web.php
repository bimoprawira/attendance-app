<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\GajiController;

// Public routes
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Employee routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'employeeDashboard'])->name('dashboard');

    // Presence routes
    Route::get('/presence', [PresenceController::class, 'index'])->name('presence.index');
    Route::post('/presence/check-in', [PresenceController::class, 'checkIn'])->name('presence.checkIn');
    Route::post('/presence/check-out', [PresenceController::class, 'checkOut'])->name('presence.checkOut');
    Route::get('/presence/history', [PresenceController::class, 'history'])->name('presence.history');

    // Leave Routes
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{id}', [LeaveController::class, 'show'])->name('leaves.show');

    Route::get('/gaji-saya', [GajiController::class, 'myGaji'])->name('gaji.index');
    Route::get('/gaji/export', [GajiController::class, 'export'])->name('gaji.export');
});

// Admin routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Employee management
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('employees.store');
    Route::put('/employees/{id}', [AdminController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/employees/{id}', [AdminController::class, 'deleteEmployee'])->name('employees.delete');
    Route::get('/employees/ajax-table', [App\Http\Controllers\AdminController::class, 'ajaxEmployeesTable'])->name('employees.ajax-table');

    // Attendance management
    Route::get('/attendance', [App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/ajax-table', [App\Http\Controllers\Admin\AttendanceController::class, 'ajaxTable'])->name('attendance.ajax-table');

    // Leave management
    Route::get('/leaves', [App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves/{leave}/approve', [App\Http\Controllers\Admin\LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [App\Http\Controllers\Admin\LeaveController::class, 'reject'])->name('leaves.reject');

    // Gaji management
    Route::get('/gaji', [App\Http\Controllers\Admin\GajiController::class, 'index'])->name('gaji.index');
    Route::get('/gaji/create', [App\Http\Controllers\Admin\GajiController::class, 'create'])->name('gaji.create');
    Route::post('/gaji', [App\Http\Controllers\Admin\GajiController::class, 'store'])->name('gaji.store');
});
