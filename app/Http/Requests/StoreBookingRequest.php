<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Rules\BookingDatesFreeRule;
use App\Rules\BookingDatesUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from' => [
                'required',
                'date',
                'after_or_equal:today',
                new BookingDatesFreeRule(),
                new BookingDatesUniqueRule()
            ],
            'to' => [
                'required',
                'date',
                'after_or_equal:from',
            ],
            'reg_plate' => [
                'required',
                'regex:/' . Booking::REG_PLATE_REGEX . '/'
            ]
        ];
    }
}
