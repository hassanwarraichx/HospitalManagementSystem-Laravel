@component('mail::message')
    # ✅ Appointment Approved

    Dear {{ $patient->user->name ?? 'Patient' }},

    Your appointment with Dr. {{ $doctor->user->name ?? 'N/A' }} has been **approved**.

    **Details:**
    - 🗓 Date & Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, h:i A') }}
    - 📍 Status: {{ ucfirst($appointment->status) }}

    @component('mail::button', ['url' => url('/patient/dashboard')])
        View Your Dashboard
    @endcomponent

    Thank you for choosing our hospital!

    Regards,
    {{ config('app.name') }}
@endcomponent
