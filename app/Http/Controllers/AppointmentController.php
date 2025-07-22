<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AppointmentService;
use Illuminate\Validation\ValidationException;

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

            $this->service->validateAndCreateAppointment($data);

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

        return back()->with('success', '✅ Appointment status updated.');
    }

    // 🧹 Cancel method removed — canceling not allowed from patient dashboard.
}
