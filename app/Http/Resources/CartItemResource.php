<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'id' => $this->id,
            'cart_id' => $this->cart_id,
            'cart_itemable_id' => $this->cart_itemable_id,
            'cart_itemable_type' => $this->cart_itemable_type,
            'quantity' => $this->quantity,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
        ];

        if ($this->relationLoaded('cart')) {
            $structure['cart'] = new CartResource($this->cart);
        }

        if ($this->relationLoaded('cart_itemable')) {
            $itemable = $this->cart_itemable;

            $attribute = get_lower_class($itemable);
            $resource = get_pure_class($itemable) . 'Resource';
            $structure[$attribute] = new $resource($itemable);
        }

        return $structure;
    }
}
