<?php

namespace App\Rules;

use App\Models\Booking;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class BookingDatesFreeRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    public function __construct(
        /**
         * index in $data with the from date
         */
        protected string $fromField = 'from',

        /**
         * index in $data with the to date
         */
        protected string $toField = 'to'
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $from = Carbon::parse($this->data[$this->fromField]);
        $to = Carbon::parse($this->data[$this->toField]);

        if (!Booking::checkDatesAreFree($from, $to)) {
            $from = $from->format('jS F Y');
            $to = $to->format('jS F Y');

            $message = 'There are not enough free parking spaces available ';

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
