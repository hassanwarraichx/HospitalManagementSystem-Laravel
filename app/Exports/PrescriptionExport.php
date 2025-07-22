<?php

namespace App\Exports;

use App\Models\PatientProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class PrescriptionExport implements FromCollection, WithHeadings
{
    protected $patient;

    /**
     * Accept either a PatientProfile instance or patient ID.
     */
    public function __construct($patient)
    {
        if ($patient instanceof PatientProfile) {
            $this->patient = $patient->load('user'); // eager load user to avoid N+1
        } else {
            $this->patient = PatientProfile::with('user')->findOrFail($patient);
        }
    }

    /**
     * Return collection of prescription data.
     */
    public function collection()
    {
        // Load appointments with prescriptions eager loaded to avoid N+1
        $appointments = $this->patient->appointments()->with('prescription')->latest()->get();

        return $appointments->map(function ($appointment) {
            $prescription = $appointment->prescription;
            $medications = $prescription ? json_decode($prescription->medications, true) : [];

            return new Collection([
                'Patient Name'     => $this->patient->user->name ?? 'N/A',
                // Format date/time for better Excel readability
                'Appointment Time' => $appointment->appointment_time ? $appointment->appointment_time->format('Y-m-d H:i') : 'N/A',
                'Notes'            => $prescription->notes ?? 'N/A',
                'Medications'      => collect($medications)->pluck('name')->implode(', ') ?: 'N/A',
                'Doses'            => collect($medications)->pluck('dose')->implode(', ') ?: 'N/A',
                'Durations'        => collect($medications)->pluck('duration')->implode(', ') ?: 'N/A',
            ]);
        });
    }

    /**
     * Set headings for Excel columns.
     */
    public function headings(): array
    {
        return ['Patient Name', 'Appointment Time', 'Notes', 'Medications', 'Doses', 'Durations'];
    }
}
