@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-clipboard-data me-2"></i> Inventory Reports
            </h4>

            <div class="d-flex gap-2">
                {{-- Back to Dashboard --}}
                @role('admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                @elserole('pharmacist')
                <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                @endrole
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 📋 Reports Actions --}}
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>📄 Report</th>
                            <th>🔍 Description</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>All Medicines</td>
                            <td>Complete inventory report of all medicines.</td>
                            <td>
                                <a href="{{ route('reports.medicines') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
