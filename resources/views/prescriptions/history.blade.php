@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Medical History of {{ $patient->user->name }}</h2>

        {{-- ✅ Export to Excel Button --}}
        <div class="mb-3">
            <a href="{{ route('doctor.export.history', $patient->id) }}" class="btn btn-success">
                ⬇️ Export as Excel
            </a>
        </div>

        {{-- 📝 Appointment Records --}}
        @forelse($appointments as $appointment)
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    Appointment on {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}
                </div>
                <div class="card-body">
                    <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>

                    @if($appointment->prescription)
                        <p><strong>Notes:</strong> {{ $appointment->prescription->notes ?? 'N/A' }}</p>

                        <h6>Medications:</h6>
                        <ul>
                            @foreach(json_decode($appointment->prescription->medications, true) as $med)
                                <li>
                                    <strong>{{ $med['name'] }}</strong> - {{ $med['dose'] }} for {{ $med['duration'] }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No prescription available for this appointment.</p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted">No medical history available.</p>
        @endforelse

        {{-- 🔙 Back Button --}}
        <div class="mt-3">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
        </div>
    </div>
@endsection
