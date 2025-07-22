<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AppointmentStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected Appointment $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Define delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Mail message representation.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🩺 Appointment Status Updated')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your appointment on **' . \Carbon\Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') . '** with Dr. **' . optional($this->appointment->doctor->user)->name . '** has been **' . strtoupper($this->appointment->status) . '**.')
            ->action('View Appointments', url('/appointments'))
            ->line('Thank you for using our Hospital Management System.');
    }

    /**
     * Store notification in the database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Appointment ' . ucfirst($this->appointment->status),
            'message' => 'Your appointment with Dr. ' . optional($this->appointment->doctor->user)->name .
                ' on ' . \Carbon\Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') .
                ' was ' . ucfirst($this->appointment->status) . '.',
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'type' => 'status-update',
        ];
    }

    /**
     * Real-time broadcast notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'Appointment ' . ucfirst($this->appointment->status),
            'message' => 'Your appointment with Dr. ' . optional($this->appointment->doctor->user)->name .
                ' on ' . \Carbon\Carbon::parse($this->appointment->appointment_time)->format('d M Y, h:i A') .
                ' was ' . ucfirst($this->appointment->status) . '.',
            'appointment_id' => $this->appointment->id,
            'status' => $this->appointment->status,
            'type' => 'status-update',
        ]);
    }
}
