<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class BookingDatesUniqueRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function __construct(
        /**
         * provided on updates, null on creates
         */
        protected Booking|null $booking = null,

        /**
         * index in $data with the from date
         */
        protected string $fromField = 'from',

        /**
         * index in $data with the to date
         */
        protected string $toField = 'to',

        /**
         * index in $data with the reg plate
         */
        protected string $regPlateField = 'reg_plate',
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $from = Carbon::parse($this->data[$this->fromField] ?? $this->booking?->from);
        $to = Carbon::parse($this->data[$this->toField] ?? $this->booking?->to);
        $regPlate = $this->data[$this->regPlateField] ?? $this->booking?->reg_plate;

        if(Booking::query()->where('reg_plate', $regPlate)->where('from', $from)->where('to', $to)->exists()) {
            $from = $from->format('jS F Y');
            $to = $to->format('jS F Y');

            $message = 'A car with a registration plate of ' . $regPlate . ' already has a booking to park ';

            if($from === $to) {
                $fail($message . 'on ' . $from);
            } else {
                $fail($message . 'between ' . $from . ' and ' . $to);
            }
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
