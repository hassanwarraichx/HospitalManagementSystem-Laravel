<?php

namespace App\Exports;

use App\Models\PatientProfile;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MedicalHistoryExport implements FromView
{
    protected $patient;

    public function __construct(PatientProfile $patient)
    {
        $this->patient = $patient;
    }

    public function view(): View
    {
        $appointments = $this->patient->appointments()->with('prescription')->latest()->get();

        return view('exports.medical_history', [
            'patient' => $this->patient,
            'appointments' => $appointments
        ]);
    }
}
