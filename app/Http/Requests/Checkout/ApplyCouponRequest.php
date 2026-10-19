<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coupon_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'reward_code' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gift_certificate_code' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }
}