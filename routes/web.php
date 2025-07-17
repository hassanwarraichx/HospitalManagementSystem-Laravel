<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;

// 🌐 Public Welcome Page
Route::get('/', function () {
return view('welcome');
});

// ✅ Auth routes from your friend's update
Auth::routes(); // this registers login, register, etc.

// 🧑 Authenticated User Dashboard
Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// 🧑 PATIENT Routes (Appointment Creation)
Route::middleware(['auth', 'role:patient'])->group(function () {
Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// 🧑‍⚕️ DOCTOR / ADMIN Routes (Appointment Management)
Route::middleware(['auth', 'role:doctor|admin'])->group(function () {
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::patch('/appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
});
