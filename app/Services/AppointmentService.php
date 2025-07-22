<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AppointmentStatusChanged;
use App\Notifications\AppointmentCreatedNotification;
use App\Notifications\AppointmentBookedNotification;

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

    /**
     * Validate and create a new appointment.
     */
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

        $time = now()->parse($data['appointment_time']);
        $bufferStart = $time->copy()->subMinutes(30);
        $bufferEnd = $time->copy()->addMinutes(30);

        $alreadyBooked = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereBetween('appointment_time', [$bufferStart, $bufferEnd])
            ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'appointment_time' => '⚠️ This doctor is already booked near this time. Please select a different slot.',
            ]);
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $data['doctor_id'],
            'appointment_time' => $data['appointment_time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        try {
            // Notify Patient (DB + Realtime, NO Email)
            $patientUser = optional(PatientProfile::find($patientId))->user;
            if ($patientUser) {
                Notification::send($patientUser, new AppointmentCreatedNotification($appointment));
            }

            // ✅ Notify all Admins (DB + Realtime)
            $admins = User::role('admin')->get();
            Notification::send($admins, new AppointmentBookedNotification($appointment));

        } catch (\Throwable $e) {
            logger()->warning('⚠️ Notification error: ' . $e->getMessage());
        }

        return $appointment;
    }

    /**
     * Get appointments based on user role.
     */
    public function getAppointmentsForUser()
    {
        $user = Auth::user();

        return match (true) {
            $user->hasRole('doctor') && $user->doctorProfile =>
            Appointment::where('doctor_id', $user->doctorProfile->id)->latest()->get(),

            $user->hasRole('patient') && $user->patientProfile =>
            Appointment::where('patient_id', $user->patientProfile->id)->latest()->get(),

            $user->hasRole('admin') =>
            Appointment::latest()->get(),

            default => collect(),
        };
    }

    /**
     * Update status and notify patient (Email sent here on approval).
     */
    public function updateStatus(Appointment $appointment, string $status)
    {
        $appointment->update(['status' => $status]);

        try {
            if ($appointment->patient?->user) {
                Notification::send($appointment->patient->user, new AppointmentStatusChanged($appointment));
            }
        } catch (\Throwable $e) {
            logger()->warning('⚠️ Status update notification failed: ' . $e->getMessage());
        }
    }
}
