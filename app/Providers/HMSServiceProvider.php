<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AppointmentService;

class HMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind AppointmentService as a singleton or regular binding
        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService();
        });

        // 🔁 In the future, bind more services here (DoctorService, NotificationService etc.)
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // You can define event listeners, macros, or custom validators here if needed.
    }
}
