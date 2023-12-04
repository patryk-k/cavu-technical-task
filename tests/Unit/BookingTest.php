<?php

namespace Tests\Unit;

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test checking a booking can be created using a factory
     */
    public function test_model_can_be_instantiated(): void
    {
        $this->assertModelExists(Booking::factory()->create());
    }

    /**
     * A test checking a booking deletes its tokens when being deleted
     */
    public function test_model_deleted_successfully(): void
    {
        $booking = Booking::factory()->create();
        $token = $booking->createToken('Booking Token');

        $booking->delete();

        $this->assertModelMissing($booking);
        $this->assertModelMissing($token->accessToken);
    }
}
