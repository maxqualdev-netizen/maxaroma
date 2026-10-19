<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingOptionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_mode_id' => [
                'nullable',
                'integer',
            ],

            'signature' => [
                'nullable',
                'boolean',
            ],

            'insurance' => [
                'nullable',
                'boolean',
            ],

            'signature_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'insurance_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'address' => [
                'nullable',
                'array',
            ],
        ];
    }
}