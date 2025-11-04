<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPdf\Facades\Pdf;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Doctor Queue Authorization - now handled by Spatie permissions
        Gate::define('accessDoctorQueue', function ($user) {
            return $user->hasPermissionTo('access-doctor-queue') && $user->is_active;
        });

        // Configure default Browsershot settings globally via config
        // The config file sets chrome_path and no_sandbox
        // Additional args are set via environment variable handling in Browsershot
    }
}
