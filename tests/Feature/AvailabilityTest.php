<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check that availability returns a 200 response with data in an expected format
     */
    public function test_get_availability(): void
    {
        $response = $this->get(route('availability', [
            'from' => now()->format('Y-m-d'),
            'to' => now()->addWeek()->format('Y-m-d')
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'min_free_spaces',
            'total_price',
            'days' => [
                [
                    'day',
                    'price',
                    'free_spaces',
                ]
            ]
        ]);
    }
}
