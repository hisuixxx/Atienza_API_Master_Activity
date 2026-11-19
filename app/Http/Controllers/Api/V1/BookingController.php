<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\V1\BookingResource;
use App\Http\Filters\V1\BookingFilter;
use App\Http\Requests\Api\V1\StoreBookingRequest;
use App\Http\Requests\Api\V1\ReplaceBookingRequest;

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
     * Validates the body with StoreBookingRequest, then creates the booking.
     * Laravel answers 201 Created automatically for a newly created resource.
     */
    public function store(StoreBookingRequest $request)
    {
        // mappedAttributes() converts the nested JSON into database columns
        return new BookingResource(
            Booking::create($request->mappedAttributes())
        );
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * DELETE /api/v1/bookings/{booking}
     * Deletes the booking. A missing booking never reaches this method: Laravel answers 404.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return $this->ok('Booking successfully deleted');
    }


    /**
     * PUT /api/v1/bookings/{booking}
     * Replaces the whole booking: every field must be sent (see ReplaceBookingRequest).
     */
    public function replace(ReplaceBookingRequest $request, Booking $booking)
    {
        $booking->update($request->mappedAttributes());

        return new BookingResource($booking);
    }
}
