@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- ✅ Real-time Toast Notification --}}
        <div id="realtime-toast" class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3 shadow"
             role="alert" aria-live="assertive" aria-atomic="true" style="display: none; z-index: 1055;">
            <div class="d-flex">
                <div class="toast-body" id="toast-body">
                    🔔 You have a new notification!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" aria-label="Close"
                        onclick="hideToast()"></button>
            </div>
        </div>

        {{-- 👋 Welcome --}}
        <div class="mb-4">
            <h2>👋 Welcome, {{ $user->name }}</h2>
            <p class="text-muted">Here is your appointment overview and real-time updates.</p>
        </div>

        {{-- 🔢 Dashboard Stats --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title">📅 Total Appointments</h5>
                        <p class="display-6 fw-bold mb-0">{{ $appointments->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-start border-4 border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title">🔔 Unread Notifications</h5>
                        <p class="display-6 fw-bold mb-0" id="unread-count">{{ $unreadNotificationsCount }}</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('patient.appointments.create', ['from' => 'dashboard']) }}"
                   class="btn btn-outline-primary w-100 h-100 d-flex align-items-center justify-content-center fs-5 fw-semibold">
                    ➕ Book New Appointment
                </a>
            </div>
        </div>

        {{-- 🩺 Appointment Table --}}
        <h4 class="mt-5 mb-3">📖 Your Appointments</h4>

        @if($appointments->isEmpty())
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i> You have no appointments yet. Click the "Book New Appointment" button above to get started!
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover shadow-sm align-middle text-center">
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
                        <tr>
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
                                @if($appointment->prescription)
                                    <a href="{{ route('patient.prescriptions.export', $appointment->id) }}"
                                       class="btn btn-sm btn-outline-primary mb-1" title="Download Prescription">
                                        <i class="bi bi-file-earmark-arrow-down"></i> Rx
                                    </a>
                                @endif

                                @if($appointment->bill)
                                    <a href="{{ route('patient.bills.export', $appointment->id) }}"
                                       class="btn btn-sm btn-outline-success mb-1" title="Download Bill">
                                        <i class="bi bi-file-earmark-spreadsheet"></i> Bill
                                    </a>
                                @endif

                                @if(!$appointment->prescription && !$appointment->bill)
                                    <span class="text-muted">N/A</span>
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

@push('scripts')
    <script>
        function hideToast() {
            const toast = document.getElementById('realtime-toast');
            toast.style.display = 'none';
        }

        function showToast(message) {
            const toast = document.getElementById('realtime-toast');
            const toastBody = document.getElementById('toast-body');
            toastBody.textContent = "🔔 " + message;
            toast.style.display = 'block';

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                hideToast();
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Echo !== 'undefined') {
                Echo.private('App.Models.User.{{ $user->id }}')
                    .notification((notification) => {
                        console.log("🔔 Real-Time Notification:", notification);

                        const message = notification?.message || notification?.title || "New notification received.";

                        // Display toast
                        showToast(message);

                        // Update unread counter
                        const unreadElement = document.getElementById('unread-count');
                        if (unreadElement) {
                            let count = parseInt(unreadElement.textContent);
                            unreadElement.textContent = !isNaN(count) ? count + 1 : 1;
                        }
                    });
            } else {
                console.warn("⚠️ Echo is not defined. Real-time features may not work.");
            }
        });
    </script>
@endpush
