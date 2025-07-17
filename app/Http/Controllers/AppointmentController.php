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

    public function create()
    {
        $doctors = $this->service->getDoctors();
        $patients = Auth::user()->hasRole('admin') ? $this->service->getPatients() : null;

        return view('appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        try {
            $this->service->validateAndCreateAppointment($request->all());
            return redirect()->route('appointments.create')->with('success', '✅ Appointment scheduled successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function index()
    {
        $appointments = $this->service->getAppointmentsForUser();
        return view('appointments.index', compact('appointments'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $this->service->updateStatus($appointment, $request->status);

        return back()->with('success', '✅ Appointment status updated successfully!');
    }
}
