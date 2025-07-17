<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DashboardController extends Controller
{
    /**
     * Display a role-based dashboard view with appointment data.
     */
    public function index()
    {
        $user = Auth::user();

        // Doctor Dashboard
        if ($user->hasRole('doctor')) {
            $appointments = optional($user->doctorProfile)
                ? $user->doctorProfile->appointments()->latest()->get()
                : collect();

            return view('dashboard.doctor', compact('appointments'));
        }

        // Patient Dashboard
        if ($user->hasRole('patient')) {
            $appointments = optional($user->patientProfile)
                ? $user->patientProfile->appointments()->latest()->get()
                : collect();

            return view('dashboard.patient', compact('appointments'));
        }

        // Admin Dashboard
        return view('dashboard.admin');
    }
}
