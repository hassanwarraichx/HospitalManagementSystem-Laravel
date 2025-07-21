@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Register New Doctor</h2>

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

        <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            {{-- Password --}}
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            {{-- Confirm Password --}}
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            {{-- Profile Picture --}}
            <div class="form-group mb-3">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
            </div>

            {{-- Specialization --}}
            <div class="form-group mb-3">
                <label for="specialization_id">Specialization</label>
                <select name="specialization_id" class="form-control" required>
                    <option value="" disabled selected>Select specialization</option>
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization->id }}" {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                            {{ $specialization->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Availability --}}
            <hr>
            <h5 class="mt-4">Weekly Availability</h5>
            <div id="availability-wrapper">
                @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                    <div class="form-group mb-3">
                        <label for="availability[{{ $day }}]">{{ ucfirst($day) }} (Start - End)</label>
                        <div class="d-flex gap-2">
                            <input type="time" name="availability[{{ $day }}][0][start]" class="form-control">
                            <input type="time" name="availability[{{ $day }}][0][end]" class="form-control">
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-success">Register Doctor</button>
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
