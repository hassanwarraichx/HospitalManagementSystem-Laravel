@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Welcome, Admin!</h1>
        <p>This is your dashboard. From here, you can manage doctors, patients, appointments, and more.</p>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-primary">Manage Patients</a>
{{--        <a href="{{ route('admin.doctors.index') }}" class="btn btn-secondary">Manage Doctors</a>--}}

    </div>
@endsection
