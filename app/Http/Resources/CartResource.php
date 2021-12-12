<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $structure = [
            'user_id' => $this->user_id,
            'cartable_type' => $this->cartable_type,
            'cartable_id' => $this->cartable_id,
            'quantity' => $this->quantity,
            'price' => 0,
            'total' => 0,
        ];

        if ($this->relationLoaded('cartable')) {
            $cartable = $this->cartable;
            $structure['price'] = $cartable->getPrice();
            $structure['total'] = $structure['price'] * $structure['quantity'];
        }

        return $structure;
    }
}
