<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('doctor')) {
            $appointments = $user->doctorProfile
                ? $user->doctorProfile->appointments()->latest()->get()
                : collect();
            return view('dashboard.doctor', compact('appointments'));
        }

        if ($user->hasRole('patient')) {
            $appointments = $user->patientProfile
                ? $user->patientProfile->appointments()->latest()->get()
                : collect();
            return view('dashboard.patient', compact('appointments'));
        }

        return view('dashboard.admin'); // optional admin view
    }
}
