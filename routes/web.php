<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;

/*
|--------------------------------------------------------------------------
| 🌐 Web Routes - Hospital Management System
|--------------------------------------------------------------------------
| Routes for authentication, appointments, and dashboards by role.
|--------------------------------------------------------------------------
*/

// 🏠 Public Welcome Page
Route::get('/', fn() => view('welcome'))->name('home');

// 🔐 Authentication Routes (Guests Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Logout (Authenticated Users)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 🧭 Dashboard for All Authenticated Users
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 🧑 Patient Routes - Can only create appointments
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// 🛡️ Admin Routes - Full access: dashboard, create, view, manage appointments
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// 👨‍⚕️ Doctor + Admin - View and update appointment status
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});

// 🚨 Optional: Add fallback for undefined routes
// Route::fallback(fn() => response()->view('errors.404', [], 404));
