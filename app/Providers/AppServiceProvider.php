<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $canAccessAdminArea = function (User $user) {
            return $user->email === env('OWNER_EMAIL', 'admin@studio.com');
        };

        Gate::define('access-admin-area', $canAccessAdminArea);
        Gate::define('access-owner-area', $canAccessAdminArea);
    }
}
