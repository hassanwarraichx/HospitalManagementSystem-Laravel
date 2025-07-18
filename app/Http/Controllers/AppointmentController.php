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

    // ✅ Show the Create Appointment Form
    public function create()
    {
        $doctors = $this->service->getDoctors();
        $patients = Auth::user()->hasRole('admin') ? $this->service->getPatients() : null;

        return view('appointments.create', compact('doctors', 'patients'));
    }

    // ✅ Store Appointment Based on Role
    public function store(Request $request)
    {
        try {
            $this->service->validateAndCreateAppointment($request->all());

            // 🔁 Redirect to correct "create" route based on role
            if (Auth::user()->hasRole('admin')) {
                return redirect()->route('admin.appointments.create')
                    ->with('success', '✅ Appointment scheduled successfully!');
            } elseif (Auth::user()->hasRole('patient')) {
                return redirect()->route('patient.appointments.create')
                    ->with('success', '✅ Appointment scheduled successfully!');
            }

            // Fallback (just in case)
            return back()->with('success', '✅ Appointment scheduled!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    // ✅ Show Appointments (Admin/Doctor/Patient)
    public function index()
    {
        $appointments = $this->service->getAppointmentsForUser();
        return view('appointments.index', compact('appointments'));
    }




    // ✅ Update Status (admin/doctor)
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $this->service->updateStatus($appointment, $request->status);

        return back()->with('success', '✅ Appointment status updated successfully!');
    }
}
