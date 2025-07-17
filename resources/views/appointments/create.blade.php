@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i> Set an Appointment</h4>
                <a href="{{ route('appointments.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Appointments
                </a>
            </div>

            <div class="card-body">
                {{-- ✅ Flash Success --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- ❌ Unauthorized Error --}}
                @if($errors->has('unauthorized'))
                    <div class="alert alert-danger">
                        {{ $errors->first('unauthorized') }}
                    </div>
                @endif

                {{-- ✅ Appointment Form --}}
                <form action="{{ route('appointments.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- 👤 Patient (Admin only) --}}
                    @if(Auth::user()->hasRole('admin'))
                        <div class="mb-3">
                            <label for="patient_id" class="form-label">🧑 Select Patient</label>
                            <select name="patient_id" id="patient_id"
                                    class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">-- Choose Patient --</option>
                                @foreach($patients as $pat)
                                    <option value="{{ $pat->id }}" {{ old('patient_id') == $pat->id ? 'selected' : '' }}>
                                        {{ $pat->user->name }} ({{ $pat->user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    {{-- 👨‍⚕️ Doctor --}}
                    <div class="mb-3">
                        <label for="doctor_id" class="form-label">👨‍⚕️ Select Doctor</label>
                        <select name="doctor_id" id="doctor_id"
                                class="form-select @error('doctor_id') is-invalid @enderror" required>
                            <option value="">-- Choose Doctor --</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                                    Dr. {{ $doc->user->name }} — {{ $doc->specialization->name ?? 'General' }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 📅 Appointment Time --}}
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">⏰ Appointment Date & Time</label>
                        <input type="datetime-local" id="appointment_time" name="appointment_time"
                               class="form-control @error('appointment_time') is-invalid @enderror"
                               value="{{ old('appointment_time') }}"
                               required min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('appointment_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Note: Appointments cannot be booked on Sundays.</div>
                    </div>

                    {{-- 📝 Notes --}}
                    <div class="mb-3">
                        <label for="notes" class="form-label">📝 Notes <small class="text-muted">(optional)</small></label>
                        <textarea name="notes" id="notes" class="form-control" rows="3"
                                  placeholder="Describe symptoms or preferences...">{{ old('notes') }}</textarea>
                    </div>

                    {{-- ✅ Submit --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-circle me-1"></i> Confirm Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 🚫 Prevent Sundays and Past Times --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('appointment_time');
            const now = new Date().toISOString().slice(0, 16);
            input.setAttribute('min', now);

            input.addEventListener('change', function () {
                const selectedDate = new Date(this.value);
                if (selectedDate.getDay() === 0) {
                    alert("Appointments cannot be booked on Sundays. Please select another day.");
                    this.value = "";
                }
            });
        });
    </script>
@endsection
