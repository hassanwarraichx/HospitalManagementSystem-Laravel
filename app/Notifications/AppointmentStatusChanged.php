<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusChanged extends Notification
{
    use Queueable;

    public $appointment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // you can also add 'broadcast' for real-time
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your appointment on ' . $this->appointment->appointment_time . ' with Dr. ' . $this->appointment->doctor->user->name . ' has been ' . strtoupper($this->appointment->status) . '.')
            ->action('View Appointments', url('/appointments'))
            ->line('Thank you for using our Hospital Management System.');
    }

    /**
     * Store notification in database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Your appointment with Dr. ' . $this->appointment->doctor->user->name . ' was ' . $this->appointment->status . '.',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
