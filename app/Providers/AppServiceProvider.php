<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Policies\V1\BookingPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\V1\UserPolicy;

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
        // Booking models are protected by BookingPolicy
        Gate::policy(Booking::class, BookingPolicy::class);

        // User models are protected by UserPolicy
        Gate::policy(User::class, UserPolicy::class);
    }
}
