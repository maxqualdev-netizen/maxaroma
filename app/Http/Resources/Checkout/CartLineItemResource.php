<?php

namespace App\Http\Resources\Checkout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartLineItemResource extends JsonResource
{
    /**
     * Transform cart line item into checkout response.
     *
     * Item structure is kept source-agnostic.
     */
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            return $this->resource;
        }

        return [
            'id' => $this->id ?? null,
        ];
    }
}