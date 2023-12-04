<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Rules\BookingDatesFreeRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
                'nullable',
                'date',
                'after_or_equal:today',
                new BookingDatesFreeRule('from', 'to', $this->route('booking'))
            ],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:' . (request()->has('from') ? 'from' : request()->route('booking')->from),
                new BookingDatesFreeRule('from', 'to', $this->route('booking'))
            ],
            'reg_plate' => [
                'nullable',
                'regex:/' . Booking::REG_PLATE_REGEX . '/'
            ]
        ];
    }
}
