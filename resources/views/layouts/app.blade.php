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
</body>
</html>
