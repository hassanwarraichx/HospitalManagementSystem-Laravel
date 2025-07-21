@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Doctor</h2>

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

        <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name) }}" required>
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email) }}" required>
            </div>

            {{-- Password (optional) --}}
            <div class="form-group mb-3">
                <label for="password">New Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                <input type="password" name="password" class="form-control">
            </div>

            {{-- Specialization --}}
            <div class="form-group mb-3">
                <label for="specialization_id">Specialization</label>
                <select name="specialization_id" class="form-control" required>
                    <option value="" disabled>Select specialization</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}"
                            {{ old('specialization_id', $doctor->doctorProfile->specialization_id) == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Profile Picture --}}
            <div class="form-group mb-3">
                <label for="profile_picture">Profile Picture</label>
                @if ($doctor->profile_picture)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $doctor->profile_picture) }}" alt="Current Picture" width="80" height="80" class="rounded-circle">
                    </div>
                @endif
                <input type="file" name="profile_picture" class="form-control">
            </div>

            {{-- Availability --}}
            <h5 class="mt-4">Availability</h5>
            @php
                $availability = old('availability', $doctor->doctorProfile->availability ?? []);
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            @endphp

            @foreach ($days as $day)
                @php
                    $slots = $availability[$day] ?? [];
                    $firstSlot = $slots[0] ?? ['start' => '', 'end' => ''];
                @endphp
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label text-capitalize">{{ $day }}</label>
                    <div class="col-sm-5">
                        <input type="time" name="availability[{{ $day }}][0][start]" class="form-control"
                               value="{{ old("availability.$day.0.start", $firstSlot['start']) }}">
                    </div>
                    <div class="col-sm-5">
                        <input type="time" name="availability[{{ $day }}][0][end]" class="form-control"
                               value="{{ old("availability.$day.0.end", $firstSlot['end']) }}">
                    </div>
                </div>
            @endforeach

            <button type="submit" class="btn btn-success">Update Doctor</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
