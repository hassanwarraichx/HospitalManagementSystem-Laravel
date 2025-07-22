@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="card-title mb-3 text-primary">👩‍⚕️ Admin Dashboard</h2>
            <p class="card-text">Welcome <strong>{{ Auth::user()->name }}</strong>! Here's an overview of what you can manage:</p>

            <div class="row mt-4">
                {{-- Doctors --}}
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card text-white bg-primary h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title">Doctors</h5>
                                <p class="card-text small">Manage doctor profiles and specialties</p>
                            </div>
                            <i class="bi bi-person-badge fs-1"></i>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('admin.doctors.index') }}" class="text-white small">View Doctors &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Patients --}}
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card text-white bg-success h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title">Patients</h5>
                                <p class="card-text small">View and manage registered patients</p>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('admin.patients.index') }}" class="text-white small">View Patients &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Appointments --}}
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card text-white bg-warning h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title">Appointments</h5>
                                <p class="card-text small">Approve or reject appointment requests</p>
                            </div>
                            <i class="bi bi-calendar-check fs-1"></i>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('admin.appointments.index') }}" class="text-white small">View Appointments &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Reports --}}
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card text-white bg-danger h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title">Reports</h5>
                                <p class="card-text small">Access system logs and error reports</p>
                            </div>
                            <i class="bi bi-bar-chart-line fs-1"></i>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ url('/reports') }}" class="text-white small">View Reports &rarr;</a>
                        </div>
                    </div>
                </div>

                {{-- Medicines --}}
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card text-white bg-info h-100 shadow-sm">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="card-title">Medicines</h5>
                                <p class="card-text small">Manage stock, expiry dates, and export reports</p>
                            </div>
                            <i class="bi bi-capsule fs-1"></i>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('medicines.index') }}" class="text-white small">Manage Medicines &rarr;</a>
                        </div>
                    </div>
                </div>
            </div> {{-- End Row --}}

            {{-- 🔔 Real-Time Toast Notification --}}
            <div id="admin-toast" class="toast align-items-center text-white bg-dark border-0 position-fixed bottom-0 end-0 m-4"
                role="alert" aria-live="assertive" aria-atomic="true" style="display: none; z-index: 1060;">
                <div class="d-flex">
                    <div class="toast-body" id="admin-toast-message">
                        🔔 New notification received.
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            onclick="document.getElementById('admin-toast').style.display='none'"></button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
@auth
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Echo !== 'undefined') {
                Echo.private('App.Models.User.{{ Auth::id() }}')
                    .notification((notification) => {
                        console.log('🔔 Admin Notification:', notification);

                        const toast = document.getElementById('admin-toast');
                        const msg = document.getElementById('admin-toast-message');

                        const message = notification?.message || notification?.data?.message || 'You have a new notification.';
                        msg.textContent = "🔔 " + message;

                        if (toast) {
                            toast.style.display = 'block';
                            toast.classList.add('show');

                            setTimeout(() => {
                                toast.classList.remove('show');
                                toast.style.display = 'none';
                            }, 6000);
                        }
                    });
            }
        });
    </script>
@endauth
@endpush
