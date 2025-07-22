@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Doctor List</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">+ Register New Doctor</a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary bi-arrow-left"> Back to Dashboard</a>
        </div>

        @if ($doctors->count())
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Specialization</th>
                    <th>Availability</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($doctors as $index => $doctor)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            @if($doctor->profile_picture)
                                <img src="{{ asset('storage/' . $doctor->profile_picture) }}" alt="Profile Picture"
                                     width="80" height="80" class="rounded-circle">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>{{ $doctor->name }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>{{ $doctor->doctorProfile->specialization->name ?? '-' }}</td>

                        {{-- Display availability (days and time ranges) --}}
                        <td>
                            @php
                                $availability = [];

                                if ($doctor->doctorProfile) {
                                    $availabilityRaw = $doctor->doctorProfile->availability;

                                    if (is_array($availabilityRaw)) {
                                        $availability = $availabilityRaw;
                                    } elseif (is_string($availabilityRaw)) {
                                        $availability = json_decode($availabilityRaw, true) ?? [];
                                    }
                                }
                            @endphp



                            @if (!empty($availability))
                                <ul class="list-unstyled mb-0">
                                    @foreach ($availability as $day => $slots)
                                        <li>
                                            <strong>{{ ucfirst($day) }}:</strong>
                                            @foreach (is_array($slots) ? $slots : [] as $slot)
                                                <div>{{ $slot['start'] ?? '?' }} - {{ $slot['end'] ?? '?' }}</div>
                                            @endforeach
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Not Set</span>
                            @endif

                        </td>

                        <td>{{ $doctor->created_at->format('d M Y') }}</td>

                        <td>
                            <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this doctor?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                No doctors registered yet.
            </div>
        @endif
    </div>
@endsection
