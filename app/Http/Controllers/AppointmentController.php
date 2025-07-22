<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AppointmentService;
use Illuminate\Validation\ValidationException;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentStatusChanged;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentApprovedMail;

class AppointmentController extends Controller
{
    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Show the appointment creation form.
     */
    public function create(Request $request)
    {
        $doctors = $this->service->getDoctors();
        $patients = Auth::user()->hasRole('admin') ? $this->service->getPatients() : null;

        return view('appointments.create', compact('doctors', 'patients'));
    }

    /**
     * Handle storing a new appointment.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            if (Auth::user()->hasRole('patient')) {
                $data['patient_id'] = Auth::user()->patientProfile->id ?? null;
            }

            $appointment = $this->service->validateAndCreateAppointment($data);

            // Notify Doctor (real-time + toast)
            if ($appointment && $appointment->doctor && $appointment->doctor->user) {
                $doctorUser = $appointment->doctor->user;
                $doctorUser->notify(new AppointmentBookedNotification($appointment));
            }

            return redirect()
                ->route(Auth::user()->hasRole('admin') ? 'admin.appointments.create' : 'patient.dashboard')
                ->with('success', '✅ Appointment successfully ' . (Auth::user()->hasRole('admin') ? 'scheduled' : 'booked') . '!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * List all appointments based on role.
     */
    public function index()
    {
        $appointments = $this->service->getAppointmentsForUser();
        return view('appointments.index', compact('appointments'));
    }

    /**
     * Update appointment status (admin/doctor only).
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $this->service->updateStatus($appointment, $request->status);

        $patientUser = $appointment->patient->user ?? null;

        if ($patientUser) {
            // Notify patient via real-time notification
            $patientUser->notify(new AppointmentStatusChanged($appointment));

            // Send email only if approved (catch mail exceptions)
            if ($request->status === 'approved') {
                try {
                    Mail::to($patientUser->email)->send(new AppointmentApprovedMail($appointment));
                } catch (\Exception $e) {
                    \Log::error('Failed to send appointment approval email: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', '✅ Appointment status updated.');
    }
}
