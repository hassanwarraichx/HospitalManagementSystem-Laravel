@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Request Appointment</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="doctor_id">Select Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">-- Choose Doctor --</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">
                            {{ $doc->user->name }} ({{ $doc->specialization }})
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mt-3">
                <label for="appointment_time">Select Date & Time</label>
                <input type="datetime-local" name="appointment_time" class="form-control" required>
                @error('appointment_time') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="form-group mt-3">
                <label for="notes">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Request Appointment</button>
        </form>
    </div>
@endsection
