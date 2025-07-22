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
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| 🌐 Web Routes - Hospital Management System
|--------------------------------------------------------------------------
*/

// ✅ Broadcasting routes
Broadcast::routes(['middleware' => ['web', 'auth']]);
Route::get('/broadcasting/auth-test', fn () => response()->json([
    'loggedIn' => auth()->check(),
    'user' => auth()->user()
]));

// 🏠 Public Landing Page
Route::get('/', fn () => view('welcome'))->name('home');

// 🔐 Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// 🧭 Dashboard Redirection
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 🔔 Mark All Notifications as Read
Route::middleware('auth')->post('/notifications/mark-all-read', [DashboardController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');

// -------------------------------------------------------------------
// 👤 PATIENT ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'patient'])->name('dashboard');

    // 📅 Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// -------------------------------------------------------------------
// 👨‍⚕️ DOCTOR ROUTES
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'doctorDashboard'])->name('dashboard');

    // 💊 Prescriptions
    Route::get('/prescription/create/{appointment}', [PrescriptionController::class, 'create'])->name('prescription.create');
    Route::post('/prescription/store', [PrescriptionController::class, 'store'])->name('prescription.store');
    Route::get('/prescription/view/{appointment}', [PrescriptionController::class, 'view'])->name('prescription.view');

    // 📚 Patient Medical History
    Route::get('/patient/history/{patient}', [PrescriptionController::class, 'history'])->name('patient.history');

    // 📥 Export Patient History as Excel
    Route::get('/patient/history/{patient}/export', [ExportController::class, 'exportHistory'])->name('export.history');

    // 🗂️ Patient Document Viewer (NEW)
    Route::get('/patient/{patient}/documents', [DashboardController::class, 'patientDocuments'])->name('patient.documents');

    // ⬇️ Export Prescriptions for a patient (NEW)
    Route::get('/prescriptions/export/{patient}', [ExportController::class, 'exportPrescriptions'])->name('prescriptions.export');
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
    Route::resource('patients', PatientController::class)->except(['show']);

    // 🧑‍⚕️ Doctor Management
    Route::resource('doctors', DoctorController::class)->except(['show']);
});

// -------------------------------------------------------------------
// 🤝 SHARED ROUTES (Doctor + Admin)
// -------------------------------------------------------------------
Route::middleware(['auth', 'role:doctor|admin'])->patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');

// -------------------------------------------------------------------
// 💊 MEDICINES
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
// 📈 REPORTS
// -------------------------------------------------------------------
Route::prefix('reports')->middleware(['auth'])->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/medicines', [ReportController::class, 'exportMedicines'])->name('reports.medicines');
});
