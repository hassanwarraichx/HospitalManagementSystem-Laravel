@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 450px;">
            <div class="text-center mb-4">
                <h3 class="text-primary">🔐 Hospital Login</h3>
                <p class="text-muted">Please enter your credentials to continue</p>
            </div>

            {{-- ✅ Show Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ✅ Login Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">📧 Email Address</label>
                    <input type="email" name="email" class="form-control" required autofocus placeholder="you@example.com">
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">🔑 Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>

                {{-- Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
