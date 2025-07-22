@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-capsule me-2"></i> Medicines Inventory
            </h4>

            <div class="d-flex gap-2">
                {{-- Back to Dashboard --}}
                @role('admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                <a href="{{ route('medicines.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Medicine
                </a>
                @elserole('pharmacist')
                <a href="{{ route('pharmacist.dashboard') }}" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
                <a href="{{ route('medicines.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Add New Medicine
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

            {{-- 📋 Medicines Table --}}
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>💊 Name</th>
                            <th>🏷️ Brand</th>
                            <th>📦 Stock</th>
                            <th>💰 Price</th>
                            <th>⏰ Expiry Date</th>
                            <th>⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medicines as $medicine)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->brand }}</td>
                                <td>{{ $medicine->stock }}</td>
                                <td>Rs. {{ number_format($medicine->price, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}</td>
                                <td class="d-flex justify-content-center">
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this medicine?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted">No medicines found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
