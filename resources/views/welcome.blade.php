<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HMS</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(120deg, #2f8be0, #6ec6ff);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero p {
            font-size: 1.2rem;
            margin: 1rem 0 2rem;
        }

        .hero .btn {
            font-size: 1.1rem;
            padding: 0.75rem 2rem;
            border-radius: 50px;
        }

        footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            background: rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>

<div class="hero">
    <div>
        <h1>🏥 Welcome to Hospital Management System</h1>
        <p>Your health, our priority. Book appointments and manage records seamlessly.</p>
        <a href="{{ route('login') }}" class="btn btn-light text-primary shadow-lg">
            <strong>Login Now</strong>
        </a>
    </div>
</div>

<footer>
    &copy; {{ date('Y') }} HMS — All Rights Reserved.
</footer>

</body>
</html>
