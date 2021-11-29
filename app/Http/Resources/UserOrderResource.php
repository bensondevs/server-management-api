<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Traits\ApiCollectionResource;

class UserOrderResource extends JsonResource
{
    use ApiCollectionResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $structure = [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'status_description' => $this->status_description,
            'amount' => $this->raw_total,
            'customer_id' => $this->customer_id,
            'disk_size' => $this->disk_size,
            'vat_size' => $this->vat_size_percentage,
            'vat_amount' => $this->vat_amount,
            'total_amount' => $this->total_amount,
            'payment_id' => null,
        ];

        if ($this->relationLoaded('previousOrder')) {
            $structure['previous_order'] = $this->previousOrder;
        }

        if ($this->relationLoaded('reOrder')) {
            $structure['re_order'] = $this->reOrder;
        }

        if ($this->relationLoaded('container')) {
            $structure['container'] = $this->container;
        }

        if ($this->relationLoaded('customer')) {
            $structure['customer'] = $this->customer;
        }

        if ($this->relationLoaded('plan')) {
            $structure['plan'] = $this->plan;
        }

        if ($this->relationLoaded('addons')) {
            $structure['addons'] = $this->addons;
        }

        if ($this->relationLoaded('invoices')) {
            $structure['invoices'] = $this->invoices;
        }

        if ($this->relationLoaded('payment')) {
            if ($payment = $this->payment) {
                $structure['payment'] = $payment;
                $structure['payment_id'] = $payment->id;
            }
        }

        return $structure;
    }
}