@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">🧾 Generate Bill for Appointment</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Please fix the following errors:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.billing.index') }}" class="btn btn-sm btn-secondary">
                &larr; Back to Billing List
            </a>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">📋 Appointment Info</h5>
                <p><strong>Patient:</strong> {{ $appointment->patient->user->name ?? 'N/A' }}</p>
                <p><strong>Doctor:</strong> {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                <p><strong>Date:</strong>
                    {{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('d M Y, h:i A') : 'N/A' }}
                </p>
                <p><strong>Reason:</strong> {{ $appointment->notes ?? 'N/A' }}</p>
            </div>
        </div>

        <form action="{{ route('admin.billing.store', $appointment->id) }}" method="POST">
            @csrf

            {{-- Consultation Fee --}}
            <div class="form-group mb-3">
                <label for="consultation_fee">Consultation Fee (PKR)</label>
                <input type="number" name="consultation_fee" class="form-control" value="{{ old('consultation_fee') }}" required>
            </div>

            {{-- Medicine Charges --}}
            <div class="form-group mb-3">
                <label for="medicine_charges">Medicine Charges (PKR)</label>
                <input type="number" name="medicine_fee" class="form-control" value="{{ old('medicine_charges') }}">
            </div>

            {{-- Lab Test Charges --}}
            <div class="form-group mb-3">
                <label for="lab_test_charges">Lab Test Charges (PKR)</label>
                <input type="number" name="lab_fee" class="form-control" value="{{ old('lab_test_charges') }}">
            </div>

            {{-- Notes (Optional) --}}
            <div class="form-group mb-4">
                <label for="notes">Additional Notes</label>
                <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">✅ Generate Bill</button>
            <a href="{{ route('admin.billing.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
