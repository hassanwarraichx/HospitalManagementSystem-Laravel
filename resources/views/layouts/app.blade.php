<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ✅ Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    {{-- ✅ Custom Styles --}}
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

{{-- ✅ Navigation Bar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        @php
            $dashboardRoute = url('/');
            if(auth()->check()) {
                if(auth()->user()->hasRole('admin')) {
                    $dashboardRoute = route('admin.dashboard');
                } elseif(auth()->user()->hasRole('doctor')) {
                    $dashboardRoute = route('dashboard');
                } elseif(auth()->user()->hasRole('patient')) {
                    $dashboardRoute = route('dashboard');
                }
            }
        @endphp

        <a class="navbar-brand fw-bold" href="{{ $dashboardRoute }}">🏥 Hospital</a>

        @auth
            <div class="ms-auto d-flex align-items-center">
                <span class="text-white me-3">Hi, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        @endauth
    </div>
</nav>

{{-- ✅ Main Content --}}
<main class="container py-4">
    @yield('content')
</main>

{{-- ✅ Stacked Scripts --}}
@stack('scripts')

{{-- ✅ Bootstrap JS Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
