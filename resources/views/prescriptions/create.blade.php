@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Create Prescription</h2>

        <form action="{{ route('doctor.prescription.store') }}" method="POST">
            @csrf

            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

            <div class="mb-3">
                <label for="notes" class="form-label">Doctor's Notes (optional):</label>
                <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
            </div>

            <h5>Medications</h5>
            <div id="medications-list">
                <div class="medication-item row mb-3">
                    <div class="col-md-4">
                        <input type="text" name="medications[0][name]" class="form-control" placeholder="Medicine Name" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="medications[0][dose]" class="form-control" placeholder="Dose (e.g. 500mg)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="medications[0][duration]" class="form-control" placeholder="Duration (e.g. 5 days)" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-medication">✖</button>
                    </div>
                </div>
            </div>

            <button type="button" id="add-medication" class="btn btn-secondary mb-3">+ Add Medication</button>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">💾 Save Prescription</button>
                <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let index = 1;
        document.getElementById('add-medication').addEventListener('click', function () {
            const container = document.getElementById('medications-list');
            const html = `
        <div class="medication-item row mb-3">
            <div class="col-md-4">
                <input type="text" name="medications[${index}][name]" class="form-control" placeholder="Medicine Name" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="medications[${index}][dose]" class="form-control" placeholder="Dose" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="medications[${index}][duration]" class="form-control" placeholder="Duration" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-medication">✖</button>
            </div>
        </div>`;
            container.insertAdjacentHTML('beforeend', html);
            index++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-medication')) {
                e.target.closest('.medication-item').remove();
            }
        });
    </script>
@endpush
