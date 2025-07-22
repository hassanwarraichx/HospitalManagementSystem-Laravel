<?php

namespace App\Exports;

use App\Models\Appointment;
use App\Models\PatientProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PrescriptionExport implements FromCollection, WithHeadings
{
    protected $patient;

    public function __construct(PatientProfile $patient)
    {
        $this->patient = $patient;
    }

    public function collection()
    {
        return $this->patient->appointments()->with('prescription')->latest()->get()->map(function ($appointment) {
            $prescription = $appointment->prescription;
            $medications = $prescription ? json_decode($prescription->medications, true) : [];

            return [
                'Patient Name' => $appointment->patient->user->name ?? 'N/A',
                'Appointment Time' => $appointment->appointment_time,
                'Notes' => $prescription->notes ?? 'N/A',
                'Medications' => collect($medications)->pluck('name')->implode(', ') ?? 'N/A',
                'Doses' => collect($medications)->pluck('dose')->implode(', ') ?? 'N/A',
                'Durations' => collect($medications)->pluck('duration')->implode(', ') ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return ['Patient Name', 'Appointment Time', 'Notes', 'Medications', 'Doses', 'Durations'];
    }
}
