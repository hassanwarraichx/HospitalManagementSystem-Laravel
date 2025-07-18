<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\PatientController;

/*
|--------------------------------------------------------------------------
| 🌐 Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// 🏠 Landing Page
Route::get('/', fn() => view('welcome'))->name('home');

// 🔐 Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 🧭 Universal Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');


// 🧑 Patient Routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});


// 🛡️ Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // Appointments
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');


});


// 👨‍⚕️ Doctor + Admin Shared Routes
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});


// ✅ Optional: Global fallback for `appointments.store` if used without prefix
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth')->name('appointments.store');
Route::get('/appointments/create', [AppointmentController::class, 'create'])->middleware('auth')->name('appointments.create');

// 🚨 Fallback
// Route::fallback(fn() => response()->view('errors.404', [], 404));
