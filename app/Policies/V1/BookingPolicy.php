<?php

namespace App\Policies\V1;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Permissions\V1\Abilities;

class BookingPolicy
{
    /**
     * Authorization rules for bookings. Each method returns true (allowed) or false (denied).
     * The rules read the abilities stored in the user's JWT.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * May this user create a booking? Needs the broad or the "own" create ability.     */
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateBooking)
            || $user->tokenCan(Abilities::CreateOwnBooking);
    }

    /**
     * May this user update this booking (PATCH)?
     */
    public function update(User $user, Booking $booking): bool
    {
        // Broad ability: any booking
        if ($user->tokenCan(Abilities::UpdateBooking)) {
            return true;
        }

        // "Own" ability: only a booking the user owns
        if ($user->tokenCan(Abilities::UpdateOwnBooking)) {
            return $user->id === $booking->user_id;
        }

        return false;
    }

    /**
     * May this user replace this booking (PUT)? Managers only.
     */
    public function replace(User $user, Booking $booking): bool
    {
        return $user->tokenCan(Abilities::ReplaceBooking);
    }

    /**
     * May this user delete this booking?
     */
    public function delete(User $user, Booking $booking): bool
    {
         
    }


    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        // Broad ability: any booking
        if ($user->tokenCan(Abilities::DeleteBooking)) {
            return true;
        }

        // "Own" ability: only a booking the user owns
        if ($user->tokenCan(Abilities::DeleteOwnBooking)) {
            return $user->id === $booking->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }
}
