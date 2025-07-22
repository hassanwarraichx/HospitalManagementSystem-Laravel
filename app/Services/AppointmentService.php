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
    /**
     * Get all doctors with user and specialization loaded.
     */
    public function getDoctors()
    {
        return DoctorProfile::with(['user', 'specialization'])
            ->whereHas('user', fn ($q) => $q->whereNull('deleted_at'))
            ->get();
    }

    /**
     * Get all patients with user loaded.
     */
    public function getPatients()
    {
        return PatientProfile::with(['user'])
            ->whereHas('user', fn ($q) => $q->whereNull('deleted_at'))
            ->get();
    }

    /**
     * Validate and create a new appointment.
     *
     * @throws ValidationException
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

        // Parse appointment time safely
        $time = now()->parse($data['appointment_time']);
        $bufferStart = $time->copy()->subMinutes(30);
        $bufferEnd = $time->copy()->addMinutes(30);

        // Check for overlapping appointments for the doctor
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
            // Notify patient (no mail)
            $patientUser = optional(PatientProfile::find($patientId))->user;
            if ($patientUser) {
                $patientUser->notify(new AppointmentCreatedNotification($appointment));
            }

            // Notify all admins (real-time broadcast + database)
            $admins = User::role('admin')->get();
            Notification::send($admins, new AppointmentBookedNotification($appointment));
        } catch (\Throwable $e) {
            logger()->warning('⚠️ Appointment creation notifications failed: ' . $e->getMessage());
        }

        return $appointment;
    }

    /**
     * Get appointments filtered by the authenticated user's role.
     *
     * @return \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection|static[]
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
     * Update appointment status and notify patient.
     */
    public function updateStatus(Appointment $appointment, string $status): void
    {
        $appointment->update(['status' => $status]);

        try {
            $user = $appointment->patient?->user;

            if ($user) {
                $notification = new AppointmentStatusChanged($appointment);

                // Only send mail if status is 'approved'
                if ($status !== 'approved') {
                    $notification->withoutMail();
                }

                $user->notify($notification);
            }
        } catch (\Throwable $e) {
            logger()->warning('⚠️ Failed to send status change notification: ' . $e->getMessage());
        }
    }
}
