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
        // Doctor Queue Authorization Gate
        Gate::define('accessDoctorQueue', function ($user) {
            return $user->getRoleNames()->contains('doctor') && $user->is_active;
        });
    }
}
