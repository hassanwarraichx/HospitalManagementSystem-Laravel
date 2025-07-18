@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Patient List</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">+ Register New Patient</a>
        </div>

        @if ($patients->count())
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Medical History</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($patients as $index => $patient)
                    <tr>
                        <td>{{ $index + 1 }}</td>



                        <td>
                            @if($patient->profile_picture)
                                <img src="{{ asset('storage/' . $patient->profile_picture) }}" alt="Profile Picture"
                                     width="80" height="80" class="rounded-circle">
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>

                        <td>{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->patientProfile->dob ?? '-' }}</td>
                        <td>{{ ucfirst($patient->patientProfile->gender ?? '-') }}</td>
                        <td>{{ $patient->patientProfile->phone ?? '-' }}</td>
                        <td>{{ $patient->patientProfile->address ?? '-' }}</td>

                        {{-- Medical History --}}
                        <td>
                            @if($patient->medicalHistories->count())
                                <ul class="list-unstyled mb-0">
                                    @foreach($patient->medicalHistories as $history)
                                        <li class="mb-1">
                                            {{ $history->description }}
                                            @if($history->document_path)
                                                <br>
                                                <a href="{{ asset('storage/' . $history->document_path) }}" target="_blank">[View Document]</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">None</span>
                            @endif
                        </td>

                        <td>{{ $patient->created_at->format('d M Y') }}</td>

                        <td>
                            <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
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
                No patients registered yet.
            </div>
        @endif
    </div>
@endsection
