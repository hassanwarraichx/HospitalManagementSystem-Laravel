@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i> Appointment List
                </h4>

                <div class="d-flex gap-2">
                    @role('admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.appointments.create') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Set Appointment
                    </a>
                    @elserole('patient')
                    <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Set Appointment
                    </a>
                    @endrole
                </div>
            </div>

            <div class="card-body">

                {{-- ✅ Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 🔔 Unread Notifications --}}
                @if(auth()->check() && auth()->user()->unreadNotifications->count())
                    <div class="alert alert-info">
                        <strong>🔔 New Notifications:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach(auth()->user()->unreadNotifications as $notification)
                                <li>{{ $notification->data['message'] ?? '📢 You have a new update.' }}</li>
                            @endforeach
                        </ul>
                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Mark all as read</button>
                        </form>
                    </div>
                @endif

                {{-- 📋 Appointment Table --}}
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover align-middle text-center shadow-sm">
                        <thead class="table-light">
                        <tr>
                            <th>👤 Patient</th>
                            <th>🩺 Doctor</th>
                            <th>📅 Date & Time</th>
                            <th>📌 Status</th>
                            <th>📝 Notes</th>
                            <th>⚙️ Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ optional($appointment->patient->user)->name ?? 'N/A' }}</td>
                                <td>
                                    Dr. {{ optional($appointment->doctor->user)->name ?? 'N/A' }}<br>
                                    <small class="text-muted">
                                        ({{ optional($appointment->doctor->specialization)->name ?? 'General' }})
                                    </small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, h:i A') }}
                                </td>
                                <td>
                                    <span class="badge
                                        @if($appointment->status === 'approved') bg-success
                                        @elseif($appointment->status === 'rejected') bg-danger
                                        @else bg-warning text-dark
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>{{ $appointment->notes ?? '—' }}</td>
                                <td>
                                    @if($appointment->status === 'pending' &&
                                        (auth()->user()->hasRole('admin') || auth()->user()->hasRole('doctor')))
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- ✅ Approve --}}
                                            <form method="POST" action="{{ route('appointments.updateStatus', $appointment->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button class="btn btn-sm btn-success" title="Approve">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </button>
                                            </form>

                                            {{-- ❌ Reject --}}
                                            <form method="POST" action="{{ route('appointments.updateStatus', $appointment->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="btn btn-sm btn-danger" title="Reject">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No appointments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
