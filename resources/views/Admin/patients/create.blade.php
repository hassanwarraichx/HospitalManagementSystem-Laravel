@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Register New Patient</h2>

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

        <form action="{{ route('admin.patients.store') }}" method="POST" enctype="multipart/form-data">
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

            {{-- Password Confirmation --}}
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            {{-- Date of Birth --}}
            <div class="form-group mb-3">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
            </div>

            {{-- Gender --}}
            <div class="form-group mb-3">
                <label for="gender">Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="" disabled selected>Select gender</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Address --}}
            <div class="form-group mb-3">
                <label for="address">Address</label>
                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
            </div>

            {{-- Phone --}}
            <div class="form-group mb-4">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" name="profile_picture" class="form-control">
            </div>

            <hr>
            <h5 class="mt-4">Medical History (Optional)</h5>

            <div id="medical-history-container">
                <div class="medical-history-block border p-3 mb-3">
                    <div class="form-group mb-2">
                        <label for="medical_histories[0][description]">Description</label>
                        <textarea name="medical_histories[0][description]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="medical_histories[0][document]">Attach Document (optional)</label>
                        <input type="file" name="medical_histories[0][document]" class="form-control">
                    </div>
                    <button type="button" class="btn btn-sm btn-danger remove-history-btn d-none">Remove</button>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-secondary mb-4" onclick="addMedicalHistory()">+ Add Another History</button>


            {{-- Submit --}}
            <button type="submit" class="btn btn-success">Register Patient</button>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    @push('scripts')
        <script>
            let historyIndex = 1;

            function addMedicalHistory() {
                const container = document.getElementById('medical-history-container');

                const block = document.createElement('div');
                block.className = 'medical-history-block border p-3 mb-3';

                block.innerHTML = `
            <div class="form-group mb-2">
                <label for="medical_histories[${historyIndex}][description]">Description</label>
                <textarea name="medical_histories[${historyIndex}][description]" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group mb-2">
                <label for="medical_histories[${historyIndex}][document]">Attach Document (optional)</label>
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
