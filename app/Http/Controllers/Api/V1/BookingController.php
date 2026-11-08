<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\V1\BookingResource;

class BookingController extends Controller
{
    /**
     *GET /api/v1/bookings
     * Returns a paginated list (15 per page). Each booking is transformed by BookingResource.
     */
    public function index()
    {
        //
        // return Booking::all();
        return BookingResource::collection(Booking::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * GET /api/v1/bookings/{booking}
     * Returns one booking. Laravel loads it from the URL (route model binding)
     * and answers 404 by itself when it does not exist.
     */
    public function show(Booking $booking)
    {
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
