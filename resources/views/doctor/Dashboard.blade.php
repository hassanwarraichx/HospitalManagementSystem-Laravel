@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">👨‍⚕️ Doctor Dashboard</h2>

        {{-- ✅ Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-bold">
                📅 Upcoming Appointments
            </div>
            <div class="card-body p-0">
                @forelse($appointments as $appointment)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start">
                            <div class="mb-2 mb-md-0">
                                <p class="mb-1"><strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}</p>
                                <p class="mb-1">
                                    <strong>Status:</strong>
                                    @php $status = strtolower($appointment->status); @endphp
                                    <span class="badge bg-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </p>
                            </div>

                            {{-- 🔘 Action Buttons --}}
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('doctor.prescription.create', ['appointment' => $appointment->id]) }}" class="btn btn-sm btn-success">
                                    ➕ Prescription
                                </a>
                                <a href="{{ route('doctor.prescription.view', ['appointment' => $appointment->id]) }}" class="btn btn-sm btn-info text-white">
                                    👁️ View
                                </a>
                                <a href="{{ route('doctor.patient.history', ['patient' => $appointment->patient->id]) }}" class="btn btn-sm btn-secondary">
                                    📚 History
                                </a>

                                @if($appointment->status === 'pending')
                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-outline-success">✅ Approve</button>
                                    </form>
                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">❌ Reject</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted p-3">📭 No upcoming appointments.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
