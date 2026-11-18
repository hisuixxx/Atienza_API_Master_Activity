<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Filters\V1\BookingFilter;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use App\Models\User;

class UserBookingsController extends APIController
{
    /**
     * GET /api/v1/users/{user}/bookings
     * Lists only this user's bookings. All BookingFilter parameters work here too.
     */
    public function index(User $user, BookingFilter $filters)
    {
        return BookingResource::collection(
            Booking::where('user_id', $user->id)->filter($filters)->paginate()
        );
    }

}
