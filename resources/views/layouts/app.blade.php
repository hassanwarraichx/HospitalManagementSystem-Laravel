<<<<<<< HEAD
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
{{-- Optional Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Hospital</a>
    </div>
</nav>

{{-- Page Content --}}
<main class="py-4">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
=======
<!DOCTYPE html>
<html>
<head>
    <title>Hospital Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@auth
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" class="ms-auto">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </nav>
@endauth

@yield('content')
>>>>>>> ecaacc070649b21d906510284a6345d7f57502e0
</body>
</html>
