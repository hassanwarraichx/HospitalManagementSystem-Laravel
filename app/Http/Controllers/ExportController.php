<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MedicalHistoryExport;
use App\Exports\PrescriptionExport;

class ExportController extends Controller
{
    /**
     * Export full medical history for a patient.
     */
    public function exportHistory(PatientProfile $patient)
    {
        return Excel::download(new MedicalHistoryExport($patient), 'medical_history.xlsx');
    }

    /**
     * Export only prescriptions for a specific patient.
     */
    public function exportPrescriptions(int $patientId)
    {
        $patient = PatientProfile::with('user')->findOrFail($patientId);

        $patientName = $patient->user->name ?? 'patient';
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $patientName);

        $fileName = 'Prescriptions_' . $safeName . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PrescriptionExport($patient), $fileName);
    }
}
