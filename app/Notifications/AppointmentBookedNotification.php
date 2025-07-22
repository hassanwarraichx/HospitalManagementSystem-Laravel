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

    protected $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation for the database.
     */
    public function toDatabase($notifiable)
    {
        return $this->notificationPayload();
    }

    /**
     * Get the array representation for broadcasting.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->notificationPayload());
    }

    /**
     * Common notification data.
     */
    protected function notificationPayload()
    {
        return [
            'title' => '📝 Appointment Booked',
            'message' => '🕐 Appointment booked for ' .
                Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') . '.',
            'appointment_id' => $this->appointment->id,
            'doctor_name' => optional($this->appointment->doctor?->user)->name ?? 'N/A',
            'patient_name' => optional($this->appointment->patient?->user)->name ?? 'N/A',
            'type' => 'appointment-booked',
        ];
    }
}
