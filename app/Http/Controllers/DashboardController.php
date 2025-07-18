<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DashboardController extends Controller
{
    /**
     * Display a general dashboard view.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect to role-specific dashboards
        if ($user->hasRole('doctor')) {
            $appointments = optional($user->doctorProfile)
                ? $user->doctorProfile->appointments()->latest()->get()
                : collect();

            return view('dashboard.doctor', compact('appointments'));
        }

        if ($user->hasRole('patient')) {
            return redirect()->route('patient.dashboard');
        }

        return view('dashboard.admin');
    }

    /**
     * Show patient-specific dashboard view.
     */
    public function patient()
    {
        $user = Auth::user();

        // Get appointments for the patient
        $appointments = optional($user->patientProfile)
            ? $user->patientProfile->appointments()->latest()->get()
            : collect();

        // Count unread notifications
        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('patient.Dashboard', compact('appointments', 'user', 'unreadNotificationsCount'));
    }
}

