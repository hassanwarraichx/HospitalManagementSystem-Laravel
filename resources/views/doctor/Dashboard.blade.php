@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- ✅ Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- 🔔 Notification History --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">🔔 Notification Center</span>
                @if($notifications->count())
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button class="btn btn-sm btn-light">✅ Mark All Read</button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                @forelse($notifications as $note)
                    <div class="card mb-2 border-info shadow-sm">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between">
                                <strong class="text-primary">{{ $note->data['title'] ?? '🔔 Notification' }}</strong>
                                <span class="text-muted small">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-0">{{ $note->data['message'] ?? 'No message.' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">📭 No new notifications.</p>
                @endforelse
            </div>
        </div>

        {{-- 👨‍⚕️ Heading --}}
        <h2 class="mb-4 fw-bold">👨‍⚕️ Doctor Dashboard</h2>

        {{-- ⚠️ Low Stock Alert --}}
        @if(isset($lowStockMedicines) && count($lowStockMedicines) > 0)
            <div class="alert alert-warning border-0 shadow-sm">
                <strong>⚠️ Low Stock:</strong>
                <ul class="mb-0 ps-3">
                    @foreach($lowStockMedicines as $med)
                        <li>{{ $med->name }} - {{ $med->quantity }} left (Expiry: {{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 📅 Upcoming Appointments --}}
        <div class="card mb-4 shadow">
            <div class="card-header bg-primary text-white fw-bold">
                📅 Upcoming Appointments
            </div>
            <div class="card-body p-0">
                @forelse($appointments as $appointment)
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start">
                            <div class="mb-2 mb-md-0">
                                <p class="mb-1"><strong>👤 Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>🕒 Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}</p>
                                <p class="mb-1">
                                    <strong>Status:</strong>
                                    @php $status = strtolower($appointment->status); @endphp
                                    <span class="badge bg-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('doctor.prescription.create', $appointment->id) }}" class="btn btn-sm btn-success">➕ Prescription</a>
                                <a href="{{ route('doctor.prescription.view', $appointment->id) }}" class="btn btn-sm btn-info text-white">👁️ View</a>
                                <a href="{{ route('doctor.patient.history', $appointment->patient->id) }}" class="btn btn-sm btn-secondary">📚 History</a>
                                <a href="{{ route('doctor.patient.documents', $appointment->patient->id) }}" class="btn btn-sm btn-warning">📁 Reports</a>
                                <a href="{{ route('doctor.prescriptions.export', ['patient' => $appointment->patient->id]) }}" class="btn btn-sm btn-outline-primary">📥 Export</a>

                                @if($appointment->status === 'pending')
                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-sm btn-outline-success">✅ Approve</button>
                                    </form>
                                    <form action="{{ route('appointments.updateStatus', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-outline-danger">❌ Reject</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-3 text-muted">📭 No upcoming appointments.</div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
