<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔒 Only allow broadcasting routes for authenticated users
        Broadcast::routes([
            'middleware' => ['web', 'auth'],
        ]);

        // 📡 Load channel authorization logic
        require base_path('routes/channels.php');
    }
}
