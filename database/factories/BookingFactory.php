<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $from = new Carbon($this->faker->dateTimeBetween(now()->addDay(), now()->addMonth()));

        return [
            'from' => $from->format('Y-m-d'),
            'to' => $this->faker->dateTimeBetween($from, $from->addMonth())->format('Y-m-d'),
            'reg_plate' => $this->faker->regexify('[A-Z]{2}[0-9]{2} [A-Z]{3}')
        ];
    }

    /**
     * Booking which happened in the past
     */
    public function past(): Factory
    {
        return $this->state(function (array $attributes) {
            $from = new Carbon($this->faker->dateTimeBetween(now()->subMonths(2), now()->subMonth()));

            return [
                'from' => $from->format('Y-m-d'),
                'to' => $this->faker->dateTimeBetween($from, $from->addMonth())->format('Y-m-d'),
            ];
        });
    }
}
