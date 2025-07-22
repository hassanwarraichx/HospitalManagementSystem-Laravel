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

    {{-- ✅ Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
@if(auth()->check() && auth()->user()->hasRole('admin'))
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>

        Pusher.logToConsole = true;

        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        var channel = pusher.subscribe('medicine-alert');
        channel.bind('send-message', function(data) {
            if (data.message) {
                document.getElementById('medicineToastBody').innerText = data.message;
                var toast = new bootstrap.Toast(document.getElementById('medicineToast'));
                toast.show();
            }
        });
    </script>
@endif


{{-- ✅ Global Notification Toast --}}
<div id="global-notification" class="toast align-items-center text-white bg-info border-0 position-fixed bottom-0 end-0 m-4 shadow"
     role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; display: none;">
    <div class="d-flex">
        <div class="toast-body" id="global-notification-text">
            🔔 You have a new notification.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="document.getElementById('global-notification').style.display='none';"></button>
    </div>
</div>

{{-- ✅ Stacked Scripts --}}
@stack('scripts')

{{-- ✅ Bootstrap JS Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="medicineToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Medicine Alert</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="medicineToastBody">
            <!-- Message goes here -->
        </div>
    </div>
</div>


{{-- ✅ Notification Handler --}}
@auth
    <script>
        window.Laravel = {
            userId: {{ Auth::id() }}
        };

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Echo !== 'undefined' && window.Laravel?.userId) {
                Echo.private(`App.Models.User.${window.Laravel.userId}`)
                    .notification((notification) => {
                        console.log("🔔 Notification Received:", notification);

                        const message = notification?.message || notification?.title || notification?.data?.message || "You have a new notification";

                        const alertBox = document.getElementById("global-notification");
                        const alertText = document.getElementById("global-notification-text");

                        if (alertBox && alertText) {
                            alertText.textContent = "🔔 " + message;
                            alertBox.style.display = "block";

                            setTimeout(() => {
                                alertBox.style.display = "none";
                            }, 6000);
                        }

                        // OPTIONAL: Remove alert() fallback (clean UI)
                        // alert("🔔 " + message);
                    });
            }
        });
    </script>
@endauth

</body>
</html>
