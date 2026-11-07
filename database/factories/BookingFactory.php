<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Three random words as the booking name
            'name'      => fake()->words(3, true),
            // A random item ID reference (or you can use a factory if Item exists)
            'item_id'   => 1, 
            // One of the four allowed status codes
            'status'    => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            // A random future date and time for the reservation slot
            'slot_time' => fake()->dateTimeBetween('+1 days', '+1 months'),
            // Creates a user for the booking unless one is supplied (see recycle() below)
            'user_id'   => User::factory(),
        ];
    }
}
