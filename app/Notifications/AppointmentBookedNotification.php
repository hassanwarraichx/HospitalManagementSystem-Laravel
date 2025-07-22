<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Carbon\Carbon;

class AppointmentBookedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Only broadcast and store in DB.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // ❌ Removed 'mail'
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Appointment Booked',
            'message' => 'A new appointment has been booked for ' .
                Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') . '.',
            'appointment_id' => $this->appointment->id,
            'doctor_name' => optional($this->appointment->doctor->user)->name,
            'patient_name' => optional($this->appointment->patient->user)->name,
            'type' => 'appointment-booked',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Appointment Booked',
            'message' => 'A new appointment has been booked for ' .
                Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') . '.',
            'appointment_id' => $this->appointment->id,
            'doctor_name' => optional($this->appointment->doctor->user)->name,
            'patient_name' => optional($this->appointment->patient->user)->name,
            'type' => 'appointment-booked',
        ]);
    }
}
