<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutTotalsResource extends JsonResource
{
    /**
     * Transform pricing result into lightweight
     * checkout AJAX response.
     */
    public function toArray(Request $request): array
    {
        return [
            'subtotal' => round(
                (float) $this->subtotal,
                2
            ),

            'discount_total' => round(
                (float) $this->discountTotal,
                2
            ),

            'discounts' =>
                $this->discounts,

            'shipping_amount' => round(
                (float) $this->shippingAmount,
                2
            ),

            'insurance_amount' => round(
                (float) $this->insuranceAmount,
                2
            ),

            'signature_amount' => round(
                (float) $this->signatureAmount,
                2
            ),

            'tax_amount' => round(
                (float) $this->taxAmount,
                2
            ),

            'grand_total' => round(
                (float) $this->grandTotal,
                2
            ),

            'free_gift' =>
                $this->freeGift,

            'free_sample' =>
                $this->freeSample,
        ];
    }
}