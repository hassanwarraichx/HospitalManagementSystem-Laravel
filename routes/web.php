<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| 🌐 Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// ✅ Broadcasting routes
Broadcast::routes(['middleware' => ['web', 'auth']]);
Route::get('/broadcasting/auth-test', function () {
    return response()->json([
        'loggedIn' => auth()->check(),
        'user' => auth()->user()
    ]);
});

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

// ✅ Global Notification Marking Route
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
    // ❌ Cancel removed intentionally
});

// -------------------------------------------------------------------
// 👨‍⚕️ DOCTOR ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// -------------------------------------------------------------------
// 🛡️ ADMIN ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');

    // 📅 Appointments
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

    // 🧑‍⚕️ Doctor Management
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
});

// -------------------------------------------------------------------
// 🤝 Shared Routes (Doctor + Admin)
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
    Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});

// -------------------------------------------------------------------
// 💊 Medicines
// -------------------------------------------------------------------
Route::prefix('medicines')->middleware(['auth'])->group(function () {
    Route::get('/', [MedicineController::class, 'index'])->name('medicines.index');
    Route::get('/create', [MedicineController::class, 'create'])->name('medicines.create');
    Route::post('/', [MedicineController::class, 'store'])->name('medicines.store');
    Route::get('/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
    Route::patch('/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');
});

// -------------------------------------------------------------------
// 📈 Reports
// -------------------------------------------------------------------
Route::prefix('reports')->middleware(['auth'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/medicines', [ReportController::class, 'exportMedicines'])->name('reports.medicines');
});

// ✅ Optional fallback route
// Route::fallback(fn () => response()->view('errors.404', [], 404));
