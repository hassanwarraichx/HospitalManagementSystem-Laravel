@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i> Appointment List
                </h4>

                @role('admin')
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Set Appointment
                </a>
                @elserole('patient')
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Set Appointment
                </a>
                @endrole
            </div>

            <div class="card-body">

                {{-- ✅ Flash Success --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 🔔 Notifications --}}
                @if(auth()->check() && auth()->user()->unreadNotifications->count())
                    <div class="alert alert-info">
                        <strong>🔔 New Notifications:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach(auth()->user()->unreadNotifications as $notification)
                                <li>{{ $notification->data['message'] ?? 'You have a new update.' }}</li>
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
                    <table class="table table-bordered table-hover align-middle text-center">
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
                                {{-- Patient --}}
                                <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>

                                {{-- Doctor --}}
                                <td>
                                    Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}<br>
                                    <small class="text-muted">
                                        ({{ $appointment->doctor->specialization->name ?? 'General' }})
                                    </small>
                                </td>

                                {{-- Appointment Time --}}
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, h:i A') }}</td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge
                                        @if($appointment->status === 'approved') bg-success
                                        @elseif($appointment->status === 'rejected') bg-danger
                                        @else bg-warning text-dark
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>

                                {{-- Notes --}}
                                <td>{{ $appointment->notes ?? '—' }}</td>

                                {{-- Action --}}
                                <td>
                                    @if($appointment->status === 'pending' &&
                                         (auth()->user()->hasRole('admin') || auth()->user()->hasRole('doctor')))
                                        <form method="POST" action="{{ route('appointments.updateStatus', $appointment->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="input-group input-group-sm">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" title="Update status">
                                                    <option disabled selected>Change</option>
                                                    <option value="approved">✅ Approve</option>
                                                    <option value="rejected">❌ Reject</option>
                                                </select>
                                            </div>
                                        </form>
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
