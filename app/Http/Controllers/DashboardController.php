<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;
use App\Models\PatientProfile;

class DashboardController extends Controller
{
    /**
     * Redirect user to their appropriate dashboard based on role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole('patient')) {
            return redirect()->route('patient.dashboard');
        }

        // Admin Dashboard
        $unreadNotificationsCount = $user->unreadNotifications()->count();
        return view('dashboard.admin', compact('unreadNotificationsCount'));
    }

    /**
     * Doctor Dashboard with appointments, low stock alerts, and notifications.
     */
    public function doctorDashboard()
    {
        $user = Auth::user();

        // 🩺 Doctor's Appointments (ensure doctorProfile exists)
        $appointments = $user->doctorProfile
            ? $user->doctorProfile->appointments()->latest()->get()
            : collect();

        // ⚠️ Low Stock Medicines (threshold can be adjusted)
        $lowStockMedicines = Medicine::where('quantity', '<=', 10)->get();

        // 🔔 Notifications: latest 10 and count unread
        $notifications = $user->notifications()->latest()->take(10)->get();
        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('doctor.dashboard', compact(
            'appointments',
            'lowStockMedicines',
            'notifications',
            'unreadNotificationsCount'
        ));
    }

    /**
     * Show Patient Dashboard with appointments and notification summary.
     */
    public function patient()
    {
        $user = Auth::user();

        $appointments = $user->patientProfile
            ? $user->patientProfile->appointments()->latest()->get()
            : collect();

        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('patient.dashboard', compact('appointments', 'user', 'unreadNotificationsCount'));
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', '🔔 All notifications marked as read.');
    }

    /**
     * View documents for a specific patient (for doctors).
     */
    public function patientDocuments($patientId)
    {
        $patient = PatientProfile::with('user')->findOrFail($patientId);

        // Assuming documents is a relation or casts to array
        $documents = $patient->documents ?? collect();

        return view('doctor.patient.documents', compact('patient', 'documents'));
    }
}
