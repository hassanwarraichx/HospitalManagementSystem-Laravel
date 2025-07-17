<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AppointmentStatusChanged;

class AppointmentController extends Controller
{
    /**
     * Show form for patients to request a new appointment.
     */
    public function create()
    {
        $doctors = DoctorProfile::with('user')->get();
        return view('appointments.create', compact('doctors'));
    }

    /**
     * Store the appointment request from patient.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'appointment_time' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Prevent double booking
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_time', $request->appointment_time)
            ->exists();

        if ($exists) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.']);
        }

        Appointment::create([
            // 'patient_id' => Auth::user()->patientProfile->id, // ✅ Enable when auth is working
            'patient_id' => 1, // 🔧 Temporary hardcoded patient for testing
            'doctor_id' => $request->doctor_id,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
        ]);

        return redirect()->route('appointments.create')->with('success', 'Appointment requested successfully!');
    }

    /**
     * List all appointments for the logged-in doctor or admin.
     */
    public function index()
    {
        // if (auth()->user()->hasRole('doctor')) { // ✅ Enable when auth is working
        //     $appointments = Appointment::where('doctor_id', auth()->user()->doctorProfile->id)
        //         ->orderBy('appointment_time', 'asc')
        //         ->get();
        // } else {
        //     $appointments = Appointment::orderBy('appointment_time', 'asc')->get();
        // }

        // 🔧 Temporary logic (Assuming you're testing as doctor with id 1)
        $isDoctor = true;
        if ($isDoctor) {
            $appointments = Appointment::where('doctor_id', 1)
                ->orderBy('appointment_time', 'asc')
                ->get();
        } else {
            $appointments = Appointment::orderBy('appointment_time', 'asc')->get();
        }

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Update the status of a specific appointment (approve/reject).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

        // Send notification to patient
        // if ($appointment->patient && $appointment->patient->user) { // ✅ Enable when auth is working
        //     $appointment->patient->user->notify(new AppointmentStatusChanged($appointment));
        // }

        return redirect()->back()->with('success', 'Appointment status updated!'); // 🔧 Notification skipped for now
    }
}
