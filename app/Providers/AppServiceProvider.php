<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $canAccessAdminArea = fn (User $user) => $user->isAdmin();

        Gate::define('access-admin-area', $canAccessAdminArea);
        Gate::define('access-owner-area', $canAccessAdminArea);
    }
}
