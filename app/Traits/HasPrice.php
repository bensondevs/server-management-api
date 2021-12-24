<?php

namespace App\Traits;

use App\Models\Pricing;

trait HasPrice 
{
    /**
     * Get pricings of the model
     */
    public function pricings()
    {
        return $this->morphMany(Pricing::class, 'priceable');
    }

    /**
     * Get price of the model
     * 
     * @return  float
     */
    public function getPrice()
    {
        if (! $pricings = $this->pricings) {
            return 0;
        }

        $currency = current_currency();
        $pricing = $pricings->where('currency', $currency)->first();
        if (! $pricing) {
            return 0;
        }

        return $pricing->price;
    }

    /**
     * Set pricing for current model
     * 
     * @param  float  $price
     * @param  int  $currency
     * @return bool
     */
    public function setPrice(float $price, int $currency = Currency::EUR)
    {
        $pricing = Pricing::where('priceable_type', get_class($this))
            ->where('priceable_id', $this->attributes['id'])
            ->where('currency', $currency)
            ->first();

        if (! $pricing) {
            $pricing = new Pricing([
                'priceable_type' => get_class($this),
                'priceable_id' => $this->attributes['id'],
                'currency' => $currency,
            ]);
        }

        $pricing->price = $price;
        return $pricing->save();
    }
}