@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">👤 Welcome, {{ $user->name }}</h2>

        {{-- 🔢 Quick Stats --}}
        <div class="row mb-4">
            {{-- 📅 Total Appointments --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">📅 Total Appointments</h5>
                        <p class="display-6 fw-bold">{{ $appointments->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- 🔔 Unread Notifications --}}
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title">🔔 Unread Notifications</h5>
                        <p class="display-6 fw-bold">{{ $unreadNotificationsCount }}</p>
                    </div>
                </div>
            </div>

            {{-- ➕ Book New Appointment --}}
            <div class="col-md-4 mb-3">
                <a href="{{ route('patient.appointments.create') }}"
                   class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center fs-5 fw-semibold">
                    ➕ Book New Appointment
                </a>
            </div>
        </div>

        {{-- 🩺 Recent Appointments --}}
        <h4 class="mt-5 mb-3">🩺 Recent Appointments</h4>

        @if($appointments->isEmpty())
            <div class="alert alert-info">
                You have no appointments yet. Click the "Book New Appointment" button above to get started!
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover shadow-sm align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($appointments as $index => $appointment)
                        <tr @if($loop->first) class="table-primary" @endif>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                            <td>{{ optional($appointment->doctor->user)->name ?? 'N/A' }}</td>
                            <td>{{ optional($appointment->doctor->specialization)->name ?? 'General' }}</td>
                            <td>
                                    <span class="badge rounded-pill
                                        @if($appointment->status === 'approved') bg-success
                                        @elseif($appointment->status === 'pending') bg-warning text-dark
                                        @elseif($appointment->status === 'rejected') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                            </td>
                            <td>
                                {{-- 🔍 View & ❌ Cancel (optional logic can be added) --}}
                                <a href="#" class="btn btn-sm btn-outline-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($appointment->status === 'pending')
                                    <form action="{{ route('patient.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Cancel Appointment"
                                                onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
