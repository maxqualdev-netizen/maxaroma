<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountBreakdownResource extends JsonResource
{
    /**
     * Transform discount breakdown into checkout response.
     */
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            return $this->resource;
        }

        return [
            'type' => $this->type ?? null,
            'amount' => round(
                (float) ($this->amount ?? 0),
                2
            ),
        ];
    }
}