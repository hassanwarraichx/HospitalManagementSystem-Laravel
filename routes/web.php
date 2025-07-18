<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\MedicineController;

/*
|--------------------------------------------------------------------------
| 🌐 Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// 🏠 Public Landing Page
Route::get('/', fn () => view('welcome'))->name('home');

// 🔐 Authentication (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 🚪 Logout
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 🧭 General Dashboard Redirect Based on Role
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// -------------------------------------------------------------------
// 👤 PATIENT ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard');

    // 📅 Appointments (Patient-specific)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // ❌ Cancel appointment (optional)
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// -------------------------------------------------------------------
// 🛡️ ADMIN ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // 📅 Appointments (Admin view + create)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index'); // ✅ FIXED!
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
// 👨‍⚕️ DOCTOR & ADMIN SHARED ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});

<<<<<<< HEAD

// ✅ Optional: Global fallback for `appointments.store` if used without prefix
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth')->name('appointments.store');
Route::get('/appointments/create', [AppointmentController::class, 'create'])->middleware('auth')->name('appointments.create');

// 🚨 Fallback
// Route::fallback(fn() => response()->view('errors.404', [], 404));

// Medicines
Route::prefix('medicines')->group(function () {
    Route::get('/', [MedicineController::class, 'index'])->name('medicines.index');

    Route::get('/create', [MedicineController::class, 'create'])->name('medicines.create'); 


    Route::post('/', [MedicineController::class, 'store'])->name('medicines.store');

    Route::get('/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');

    Route::patch('/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');

    Route::delete('/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');

});

// Reports  
Route::prefix('reports')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/medicines', [ReportController::class, 'exportMedicines'])->name('reports.medicines');
});
 
=======
// ❌ Optional Fallback Route
// Route::fallback(fn () => response()->view('errors.404', [], 404));
>>>>>>> main
