@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Billing Section</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary bi-arrow-left"> Back to Dashboard</a>
        </div>

        @if ($appointments->count())
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Appointment Time</th>
                    <th>Prescription Summary</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($appointments as $index => $appointment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $appointment->patient->user->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $appointment->patient->user->email ?? '-' }}</small>
                        </td>
                        <td>
                            {{ $appointment->doctor->user->name ?? 'N/A' }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, h:i A') }}
                        </td>
                        <td>
                            {{ Str::limit($appointment->prescription->notes ?? '—', 50) }}
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">Prescription Complete</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.billing.create', $appointment->id) }}" class="btn btn-sm btn-success">
                                🧾 Generate Bill
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                No appointments found with completed prescriptions.
            </div>
        @endif
    </div>
@endsection
