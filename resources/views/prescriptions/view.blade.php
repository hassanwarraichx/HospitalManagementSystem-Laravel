@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Prescription Details</h2>

        @if($prescription)
            <div class="card">
                <div class="card-body">
                    <p><strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }}</p>
                    <p><strong>Appointment Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}</p>
                    <p><strong>Doctor's Notes:</strong> {{ $prescription->notes ?? 'None' }}</p>

                    <hr>
                    <h5>Medications</h5>
                    <ul class="list-group">
                        @foreach(json_decode($prescription->medications, true) as $med)
                            <li class="list-group-item">
                                <strong>Name:</strong> {{ $med['name'] }} |
                                <strong>Dose:</strong> {{ $med['dose'] }} |
                                <strong>Duration:</strong> {{ $med['duration'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <p class="text-muted">No prescription found for this appointment.</p>
        @endif

        <div class="mt-3">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>
    </div>
@endsection
