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

        // Doctor Dashboard
        if ($user->hasRole('doctor')) {
            $appointments = $user->doctorProfile
                ? $user->doctorProfile->appointments()->latest()->get()
                : collect();

            return view('dashboard.doctor', compact('appointments'));
        }

        // Patient Dashboard
        if ($user->hasRole('patient')) {
            return $this->patient();
        }

        // Admin Dashboard
        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('dashboard.admin', compact('unreadNotificationsCount'));
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
