<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => [
                'required',
                'string',
            ],

            'shipping_mode_id' => [
                'required',
                'integer',
            ],

            'billing' => [
                'required',
                'array',
            ],

            'shipping' => [
                'required',
                'array',
            ],

            /*
             * Payment-specific data is intentionally
             * not hard-coded here.
             *
             * Stripe / Apple Pay / Google Pay /
             * Amazon Pay / Afterpay will have their
             * own gateway-specific validation.
             */
            'payment' => [
                'nullable',
                'array',
            ],
        ];
    }
}