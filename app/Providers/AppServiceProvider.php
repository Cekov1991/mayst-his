<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    }
}
