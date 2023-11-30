<?php

namespace App\Http\Requests;

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
            'from' => 'required|date|after_or_equal:today',
            'to' => 'required|date|after_or_equal:from',
            'reg_plate' => [
                'required',
                'regex:[A-Z]{2}[0-9]{2} [A-Z]{3}'
            ]
        ];
    }
}
