@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Doctor Dashboard</h2>

        <h4 class="mt-4">Upcoming Appointments</h4>
        <ul class="list-group">
            @forelse($appointments as $appointment)
                <li class="list-group-item">
                    Patient: {{ $appointment->patient->user->name ?? 'N/A' }} |
                    Time: {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }} |
                    Status: {{ ucfirst($appointment->status) }}
                </li>
            @empty
                <li class="list-group-item text-muted">No upcoming appointments.</li>
            @endforelse
        </ul>
    </div>
@endsection
