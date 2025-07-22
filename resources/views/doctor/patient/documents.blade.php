@extends('layouts.app')

@section('content')
    <div class="container py-4">

        {{-- 🔔 Notifications --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- 👤 Patient Header --}}
        <div class="mb-4">
            <h4 class="fw-bold">📁 Documents for {{ $patient->user->name ?? 'Patient' }}</h4>
            <p class="text-muted mb-0">Patient ID: #{{ $patient->id }}</p>
            <p class="text-muted">Email: {{ $patient->user->email ?? 'N/A' }}</p>
        </div>

        {{-- 📂 Document List --}}
        <div class="card shadow">
            <div class="card-header bg-primary text-white fw-bold">
                🗂️ Uploaded Documents
            </div>
            <div class="card-body">
                @if(count($documents) > 0)
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">File Name</th>
                            <th scope="col">Uploaded At</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documents as $index => $doc)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $doc['name'] ?? 'Unnamed File' }}</td>
                                <td>{{ \Carbon\Carbon::parse($doc['uploaded_at'] ?? now())->format('d M Y h:i A') }}</td>
                                <td>
                                    @if(isset($doc['url']))
                                        <a href="{{ $doc['url'] }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener noreferrer">
                                            🔍 View / Download
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">📭 No documents uploaded yet.</p>
                @endif
            </div>
        </div>

    </div>
@endsection
