<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingMethodResource extends JsonResource
{
    /**
     * Transform shipping method into API response.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'code' => $this->code,

            'amount' => round(
                (float) ($this->amount ?? 0),
                2
            ),

            'is_selected' => (bool) (
                $this->is_selected
                ?? false
            ),

            'estimated_delivery' =>
                $this->estimated_delivery
                ?? null,
        ];
    }
}