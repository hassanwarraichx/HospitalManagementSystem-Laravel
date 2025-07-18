@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Patient</h2>

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

        <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $patient->name) }}" required>
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}" required>
            </div>

            {{-- DOB --}}
            <div class="form-group mb-3">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $patient->patientProfile->dob ?? '') }}" required>
            </div>

            {{-- Gender --}}
            <div class="form-group mb-3">
                <label for="gender">Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="male" {{ old('gender', $patient->patientProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $patient->patientProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $patient->patientProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Address --}}
            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address', $patient->patientProfile->address ?? '') }}</textarea>
            </div>

            {{-- Phone --}}
            <div class="form-group mb-3">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $patient->patientProfile->phone ?? '') }}" required>
            </div>

            {{-- Profile Picture --}}
            <div class="form-group mb-3">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
                @if($patient->profile_picture)
                    <img src="{{ asset('storage/' . $patient->profile_picture) }}" class="mt-2" alt="Profile Picture" width="100">
                @endif
            </div>

            <hr>
            <h5 class="mt-4">Medical History</h5>

            <div id="medical-history-container">
                @foreach($patient->medicalHistories as $index => $history)
                    <div class="medical-history-block border p-3 mb-3">
                        <input type="hidden" name="medical_histories[{{ $index }}][id]" value="{{ $history->id }}">
                        <div class="form-group mb-2">
                            <label>Description</label>
                            <textarea name="medical_histories[{{ $index }}][description]" class="form-control" rows="2">{{ old("medical_histories.{$index}.description", $history->description) }}</textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label>Replace Document (optional)</label>
                            <input type="file" name="medical_histories[{{ $index }}][document]" class="form-control">
                            @if($history->document_path)
                                <a href="{{ asset('storage/' . $history->document_path) }}" target="_blank" class="btn btn-sm btn-link">[View Current]</a>
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-history-btn" onclick="this.parentElement.remove()">Remove</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="addMedicalHistory()">+ Add Another History</button>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary">Update Patient</button>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @push('scripts')
        <script>
            let historyIndex = {{ $patient->medicalHistories->count() }};

            function addMedicalHistory() {
                const container = document.getElementById('medical-history-container');

                const block = document.createElement('div');
                block.className = 'medical-history-block border p-3 mb-3';

                block.innerHTML = `
                    <div class="form-group mb-2">
                        <label>Description</label>
                        <textarea name="medical_histories[${historyIndex}][description]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label>Attach Document (optional)</label>
                        <input type="file" name="medical_histories[${historyIndex}][document]" class="form-control">
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-history-btn" onclick="this.parentElement.remove()">Remove</button>
                `;

                container.appendChild(block);
                historyIndex++;
            }
        </script>
    @endpush
@endsection
