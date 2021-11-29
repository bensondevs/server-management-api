<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Stevebauman\Location\Facades\Location;

use App\Enums\ServicePlan\ServicePlanStatus;
use App\Enums\ServicePlan\ServicePlanTimeUnit;

use App\Enums\Pricing\PricingCurrency;

class ServicePlan extends Model
{
    protected $table = 'service_plans';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'plan_name',
        'plan_code',
        'status',

        'time_quantity',
        'time_unit',
        'description',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($servicePlan) {
            $servicePlan->id = Uuid::generate()->string;
    	});
    }

    public function getDurationAttribute()
    {
        $quantity = $this->attributes['time_quantity'];
        $unit = ServicePlanTimeUnit::getDescription($this->attributes['time_unit']);

        return $quantity . ' ' . $unit;
    }

    public function getDurationInDaysAttribute()
    {
        $quantity = (int) $this->attributes['time_quantity'];
        $unit = $this->attributes['time_unit'];

        if ($unit == ServicePlanTimeUnit::Year) {
            $quantity = $quantity * 365;
        } else if ($unit == ServicePlanTimeUnit::Month) {
            $quantity = $quantity * 30;
        }

        return $quantity;
    }

    public function setDurationAttribute(string $duration)
    {
        $explode = explode(' ', $duration);

        if (count($explode) == 2) {
            $this->attributes['time_quantity'] = $explode[0];
            $this->attributes['time_unit'] = $explode[1];
        }
    }

    public function getPricingAttribute()
    {
        if (! ($this->pricings)) {
            $this->pricings = $this->pricings();
        }

        // Get continent
        $continent = 'EU';
        $geoIp = geoip(request()->ip());
        if (isset($geoIp['continent'])) {
            $continent = $geoIp['continent'];
        }


        if ($continent == 'EU') {
            return $this->pricing = $this->pricings
                ->where('currency', PricingCurrency::EUR)
                ->first();
        }

        return $this->pricing = $this->pricings()
            ->where('currency', PricingCurrency::USD)
            ->first();
    }

    public function getSubscriptionFeeAttribute()
    {
        if (! isset($this->pricing)) {
            $this->pricing = $this->getPricingAttribute();
        }

        return $this->pricing ? $this->pricing->price : 0;
    }

    public function getCurrencyAttribute()
    {
        if (! isset($this->pricing)) {
            $this->pricing = $this->getPricingAttribute();
        }
        
        return $this->pricing ? $this->pricing->currency_code : 'EUR';
    }

    public function pricings()
    {
        return $this->morphMany(Pricing::class, 'priceable');
    }

    public function addPricing(array $pricingData = [])
    {
        $pricing = new Pricing();
        $pricing->fill($pricingData);
        $pricing->priceable_type = get_class($this);
        $pricing->priceable_id = $this->attributes['id'];
        return $pricing->save();
    }
}