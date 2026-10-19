<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'billing' => ['nullable', 'array'],
            'shipping' => ['nullable', 'array'],

            'billing.*' => ['nullable'],
            'shipping.*' => ['nullable'],
        ];
    }
}