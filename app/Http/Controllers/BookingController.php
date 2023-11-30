<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): Booking
    {
        // TODO: Add free space checks

        $booking = new Booking($request->validated());
        $booking->save();

        // TODO: Wrap in a BookingResource
        return $booking;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): Booking
    {
        // TODO: make sure the token is for the given booking
        // TODO: Add free space checks

        $booking->fill($request->validated());
        $booking->save();

        // TODO: Wrap in a BookingResource
        return $booking;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): Response
    {
        // TODO: make sure the token is for the given booking
        $booking->delete();

        return response()->noContent();
    }
}
