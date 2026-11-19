<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\V1\BookingResource;
use App\Http\Filters\V1\BookingFilter;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Requests\Api\V1\ReplaceBookingRequest;
use App\Http\Requests\Api\V1\UpdateBookingRequest;

class BookingController extends APIController
{
    /**
     *GET /api/v1/bookings
     * Returns a paginated list (15 per page). Each booking is transformed by BookingResource.
     */
    public function index(BookingFilter $filters)
    {
        // ?include=user: eager load the user in one extra query
        // if ($this->include('user')) {
        //     return BookingResource::collection(Booking::with('user')->paginate());
        // }

        // return Booking::all();
        // return BookingResource::collection(Booking::paginate());

        // Apply the filters from the URL (?status=confirmed, ?name=*test*, ?include=user ...)
        return BookingResource::collection(
            Booking::filter($filters)->paginate()
        );
    }

    /**
     * POST /api/v1/bookings
     * Creates a booking, but only if the policy allows this user to do so.
     */
    public function store(StoreBookingRequest $request)
    {
       // Ask BookingPolicy::store() (checks the ability stored in the token)
        if ($this->isAble('store', Booking::class)) {
            return new BookingResource(
                Booking::create($request->mappedAttributes())
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * GET /api/v1/bookings/{booking}
     * Returns one booking. Laravel loads it from the URL (route model binding)
     * and answers 404 by itself when it does not exist.
     */
    public function show(Booking $booking)
    {
        // ?include=user: load the user of this booking
        if ($this->include('user')) {
            return new BookingResource($booking->load('user'));
        }

        return new BookingResource($booking);
    }

    /**
     * PATCH /api/v1/bookings/{booking}
     * Updates only the fields that were sent, but only if the policy allows it.     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
       // Ask BookingPolicy::update()
        if ($this->isAble('update', $booking)) {
            $booking->update($request->mappedAttributes());

            return new BookingResource($booking);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**   
     * DELETE /api/v1/bookings/{booking}
     *Deletes the booking, but only if the policy allows it.
     */
    public function destroy(Booking $booking)
    {
      if ($this->isAble('delete', $booking)) {
            $booking->delete();

            return $this->ok('Booking successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
 }


    /**
     * PUT /api/v1/bookings/{booking}
     * Replaces the whole booking, but only if the policy allows it.
     */
    public function replace(ReplaceBookingRequest $request, Booking $booking)
    {
        if ($this->isAble('replace', $booking)) {
            $booking->update($request->mappedAttributes());

            return new BookingResource($booking);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }
}
