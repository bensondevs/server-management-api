<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Observers\PricingObserver;

use App\Enums\Pricing\PricingStatus;
use App\Enums\Pricing\PricingCurrency;

class Pricing extends Model
{
    protected $table = 'pricings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'currency',
        'price',
        'status',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(PricingObserver::class);

    	self::creating(function ($pricing) {
            $pricing->id = Uuid::generate()->string;
    	});
    }

    public function priceable()
    {
        return $this->morphTo();
    }

    public function getCurrencyDescriptionAttribute()
    {
        $currency = $this->attributes['currency'];
        return PricingCurrency::getDescription($currency);
    }

    public function getCurrencyCodeAttribute()
    {
        $currency = $this->attributes['currency'];
        return PricingCurrency::getKey($currency);
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return PricingStatus::getKey($status);
    }

    public function sameCurrencyPricings()
    {
        $pricing = new Pricing();
        return $pricing->where('priceable_type', $this->attributes['priceable_type'])
            ->where('priceable_id', $this->attributes['priceable_id'])
            ->where('currency', $this->attributes['currency'])
            ->where('id', '!=', $this->attributes['id'])
            ->get();
    }

    public function activate()
    {
        $this->attributes['status'] = PricingStatus::Active;
        $this->save();
    }

    public function deactivate()
    {
        $this->attributes['status'] = PricingStatus::Inactive;
        $this->save();
    }
}