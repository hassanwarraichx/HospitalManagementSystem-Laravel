<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Notifications\AppointmentStatusChanged;

class AppointmentService
{
    public function getDoctors()
    {
        return DoctorProfile::with('user')->get();
    }

    public function getPatients()
    {
        return PatientProfile::with('user')->get();
    }

    public function validateAndCreateAppointment(array $data)
    {
        $rules = [
            'doctor_id' => 'required|exists:doctor_profiles,id',
            'appointment_time' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ];

        if (Auth::user()->hasRole('admin')) {
            $rules['patient_id'] = 'required|exists:patient_profiles,id';
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $patientId = Auth::user()->hasRole('admin')
            ? $data['patient_id']
            : (Auth::user()->patientProfile->id ?? null);

        if (!$patientId) {
            throw ValidationException::withMessages([
                'unauthorized' => 'Only patients or admins can create appointments.'
            ]);
        }

        $time = $data['appointment_time'];
        $bufferStart = now()->parse($time)->subMinutes(30);
        $bufferEnd = now()->parse($time)->addMinutes(30);

        $alreadyBooked = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereBetween('appointment_time', [$bufferStart, $bufferEnd])
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'appointment_time' => '⚠️ Doctor already has an appointment near this time. Choose another slot.',
            ]);
        }

        return Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $data['doctor_id'],
            'appointment_time' => $data['appointment_time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function getAppointmentsForUser()
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole('doctor') => Appointment::where('doctor_id', $user->doctorProfile->id)->orderBy('appointment_time')->get(),
            $user->hasRole('patient') => Appointment::where('patient_id', $user->patientProfile->id)->orderBy('appointment_time')->get(),
            default => Appointment::orderBy('appointment_time')->get()
        };
    }

    public function updateStatus(Appointment $appointment, string $status)
    {
        $appointment->update(['status' => $status]);

        try {
            if ($appointment->patient?->user) {
                $appointment->patient->user->notify(new AppointmentStatusChanged($appointment));
            }
        } catch (\Exception $e) {
            logger()->warning('Notification failed: ' . $e->getMessage());
        }
    }
}
