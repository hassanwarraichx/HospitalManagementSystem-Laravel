<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| All routes for the Hospital Management System
| Grouped by roles: Patient, Doctor, Admin
|--------------------------------------------------------------------------
*/

// 🌐 Public Welcome Page
Route::get('/', function () {
    return view('welcome');
});

// 🧑 Authenticated User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// 👨‍⚕️ PATIENT Routes (Request Appointment)
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// 🩺 DOCTOR & ADMIN Routes (Manage Appointments)

// ✅ Use this in PRODUCTION with working auth and role system
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});

// ❌ TEMPORARY TESTING ROUTES (DISABLE in production)
// Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
// Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
