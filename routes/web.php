<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\PatientController;

// 🏠 Public Landing Page
Route::get('/', fn () => view('welcome'))->name('home');

// 🔐 Authentication Routes (Guests Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 🧭 Dashboard Redirection Based on Role
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ✅ Global Notification Marking Route (for all roles)
Route::middleware('auth')->post('/notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');

// -------------------------------------------------------------------
// 👤 PATIENT ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard');

    // 📅 Appointments (Patient)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // ❌ Removed cancel route
    // Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// -------------------------------------------------------------------
// 🛡️ ADMIN ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // 📅 Appointments (Admin)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // 👨‍⚕️ Patient Management
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

// -------------------------------------------------------------------
// 👨‍⚕️ DOCTOR & ADMIN SHARED ROUTES (Appointment Status Control)
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    // Status update (approve/reject)
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});

// Optional Fallback
// Route::fallback(fn () => response()->view('errors.404', [], 404));
