<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('✅ Appointment Approved')
            ->markdown('emails.appointment.approved')
            ->with([
                'appointment' => $this->appointment,
                'patient' => optional($this->appointment->patient)->user,
                'doctor' => optional($this->appointment->doctor)->user,
            ]);
    }
}
