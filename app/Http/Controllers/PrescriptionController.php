<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrescriptionExport;

class PrescriptionController extends Controller
{
    /**
     * Show the form for creating a new prescription.
     */
    public function create(Appointment $appointment)
    {
        return view('prescriptions.create', compact('appointment'));
    }

    /**
     * Store a newly created prescription in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id'      => 'required|exists:appointments,id',
            'notes'               => 'nullable|string',
            'medications'         => 'required|array',
            'medications.*.name'  => 'required|string',
            'medications.*.dose'  => 'required|string',
            'medications.*.duration' => 'required|string',
        ]);

        Prescription::create([
            'appointment_id' => $validated['appointment_id'],
            'notes'          => $validated['notes'],
            'medications'    => json_encode($validated['medications']),
        ]);

        return redirect()->route('doctor.dashboard')->with('success', '📝 Prescription saved successfully.');
    }

    /**
     * View prescription details for a specific appointment.
     */
    public function view(Appointment $appointment)
    {
        $prescription = $appointment->prescription;
        return view('prescriptions.view', compact('appointment', 'prescription'));
    }

    /**
     * Display the full medical history of a patient.
     */
    public function history(PatientProfile $patient)
    {
        $appointments = $patient->appointments()->with('prescription')->latest()->get();
        return view('prescriptions.history', compact('patient', 'appointments'));
    }

    /**
     * Export patient's medical history (prescriptions) as an Excel file.
     */
    public function export(PatientProfile $patient)
    {
        $fileName = 'Medical_History_' . str_replace(' ', '_', $patient->user->name) . '.xlsx';
        return Excel::download(new \App\Exports\PrescriptionExport($patient), $fileName);
    }

}
