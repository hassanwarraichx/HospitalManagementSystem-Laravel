<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

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
     * Doctor Dashboard with upcoming appointments.
     */
    public function doctorDashboard()
    {
        $user = Auth::user();

        $appointments = $user->doctorProfile
            ? $user->doctorProfile->appointments()->latest()->get()
            : collect();

        return view('doctor.dashboard', compact('appointments'));
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
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', '🔔 All notifications marked as read.');
    }
}
