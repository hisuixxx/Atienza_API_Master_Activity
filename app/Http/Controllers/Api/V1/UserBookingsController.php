<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Filters\V1\BookingFilter;
use App\Http\Resources\Api\V1\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Http\Requests\Api\V1\ReplaceBookingRequest;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Requests\Api\V1\UpdateBookingRequest;

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

    /**
     * POST /api/v1/users/{user}/bookings
     * Creates a booking for the user named in the URL.
     */
    public function store(StoreBookingRequest $request, User $user)
    {
        if ($this->isAble('store', Booking::class)) {
            // ['user' => 'user_id'] turns the id taken from the URL into the user_id column
            return new BookingResource(
                Booking::create($request->mappedAttributes(['user' => 'user_id']))
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * PUT /api/v1/users/{user}/bookings/{booking}
     * Replaces the whole booking (managers only).
     */
    public function replace(ReplaceBookingRequest $request, User $user, Booking $booking)
    {
        if ($this->isAble('replace', $booking)) {
            $booking->update($request->mappedAttributes());

            return new BookingResource($booking);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * PATCH /api/v1/users/{user}/bookings/{booking}
     * Updates only the fields that were sent.
     */
    public function update(UpdateBookingRequest $request, User $user, Booking $booking)
    {
        if ($this->isAble('update', $booking)) {
            $booking->update($request->mappedAttributes());

            return new BookingResource($booking);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * DELETE /api/v1/users/{user}/bookings/{booking}
     * Deletes the booking. The scoped binding returns 404 if it is not this user's booking.
     */
    public function destroy(User $user, Booking $booking)
    {
        if ($this->isAble('delete', $booking)) {
            $booking->delete();

            return $this->ok('Booking successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }

}
