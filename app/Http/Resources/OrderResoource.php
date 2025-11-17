<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResoource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone?->value,
            'status' => $this->status?->value,
            'phone' => $this->phone?->value,
            'address' => $this->address?->value,
            'proudcts' => $this->whenLoaded('product', function() {
                return $this->product->map(function($product) {
                    return [
                        'name' => $product->name,
                        'quantity' => $product->quantity,
                        'cost' => $product->cost,
                        'name' => $product->name,
                    ];
                });
            }),
        ];
    }
}
