<?php

return [

    // 🔧 Default broadcaster (set in .env as BROADCAST_DRIVER=pusher)
    'default' => env('BROADCAST_DRIVER', 'pusher'),

    'connections' => [

        // ✅ Pusher: Public Pusher.com setup (DO NOT override host/port/scheme unless self-hosting)
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true, // Ensures secure wss:// connection
                // ❌ REMOVE host/port/scheme unless you're using Laravel WebSockets!
            ],
            'client_options' => [
                // Add Guzzle options if needed
            ],
        ],

        // 📡 Ably setup
        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        // 🧠 Redis (optional alternative to Pusher)
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        // 📝 For debugging only (writes to log file)
        'log' => [
            'driver' => 'log',
        ],

        // ❌ Null broadcaster (does nothing)
        'null' => [
            'driver' => 'null',
        ],

    ],

];
