<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): BookingResource
    {
        $booking = new Booking($request->validated());
        $booking->save();

        // TODO: add token creation

        return BookingResource::make($booking->load('tokens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        // TODO: make sure the token is for the given booking

        $booking->fill($request->validated());
        $booking->save();

        return BookingResource::make($booking->load('tokens'));
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
