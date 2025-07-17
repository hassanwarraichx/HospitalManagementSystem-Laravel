@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Appointments</h2>

        {{-- ✅ Show Session Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ Show Unread Notifications (only if user is logged in) --}}
        @if(auth()->check() && auth()->user()->unreadNotifications->count())
            <div class="alert alert-info">
                <strong>You have new notifications:</strong>
                <ul class="mb-0">
                    @foreach(auth()->user()->unreadNotifications as $notification)
                        <li>{{ $notification->data['message'] }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Appointments Table --}}
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                    <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y h:i A') }}</td>
                    <td>{{ ucfirst($appointment->status) }}</td>
                    <td>{{ $appointment->notes }}</td>
                    <td>
                        @if($appointment->status === 'pending')
                            <form method="POST" action="{{ route('appointments.updateStatus', $appointment->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option selected disabled>Change Status</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </form>
                        @else
                            <span class="text-muted">No actions</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No appointments found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
