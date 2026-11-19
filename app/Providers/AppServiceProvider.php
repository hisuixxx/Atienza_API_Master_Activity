<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Policies\V1\BookingPolicy;
use Illuminate\Support\Facades\Gate;

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
    }
}
