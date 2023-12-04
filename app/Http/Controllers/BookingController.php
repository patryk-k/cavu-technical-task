<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

class BookingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): BookingResource
    {
        $booking = new Booking($request->validated());
        $booking->save();

        $booking->token = $booking->createToken('Booking Token', ['*'], $booking->to->endOfDay())->plainTextToken;

        return BookingResource::make($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        $this->authorise($request, $booking);

        $booking->fill($request->validated());
        $booking->save();

        $booking->tokens()->update([
            'expires_at' => $booking->to
        ]);

        return BookingResource::make($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Booking $booking): Response
    {
        $this->authorise($request, $booking);

        $booking->delete();

        return response()->noContent();
    }

    /**
     * Check if token in $request is attached to $booking
     *
     * @throws UnauthorizedException
     */
    private function authorise(Request $request, Booking $booking): void
    {
        // request->user() returns a Booking model object associated with the token used in this request
        if(!is_a($request->user(), Booking::class) || $request->user()->id != $booking->id) {
            throw new UnauthorizedException();
        }
    }
}
