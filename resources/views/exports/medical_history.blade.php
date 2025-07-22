<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical History Export</title>
</head>
<body>
<h2>Medical History of {{ $patient->user->name }}</h2>

@foreach($appointments as $appointment)
    <h4>Appointment on {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}</h4>
    <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>

    @if($appointment->prescription)
        <p><strong>Notes:</strong> {{ $appointment->prescription->notes ?? 'N/A' }}</p>
        <p><strong>Medications:</strong></p>
        <ul>
            @foreach(json_decode($appointment->prescription->medications, true) as $med)
                <li><strong>{{ $med['name'] }}</strong> - {{ $med['dose'] }} for {{ $med['duration'] }}</li>
            @endforeach
        </ul>
    @else
        <p>No prescription available for this appointment.</p>
    @endif

    <hr>
@endforeach
</body>
</html>
