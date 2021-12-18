<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\Currency;
use App\Observers\PricingObserver as Observer;
use App\Enums\Pricing\PricingStatus as Status;

class Pricing extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'pricings';

    /**
     * Model primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model timestamp marking enability
     * Set to TRUE to set the value of `created_at` upon model create 
     * and `updated_at` upon model updating event 
     * 
     * @var bool 
     */
    public $timestamps = true;

    /**
     * Model primary key incrementing. 
     * Set to TRUE if `id` is int, otherwise let it be FALSE
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model massive fillable columns
     * Put column names which can be assigned massively
     * 
     * @var array 
     */
    protected $fillable = [
        'currency',
        'price',
    ];

    /**
     * Model boot static method
     * This method handles event and hold event listener and observer
     * This is where Observer and Event Listener Class should be put
     * 
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Create callable method of 
     */

    /**
     * Any item that can be priced
     */
    public function priceable()
    {
        return $this->morphTo();
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return status enum description
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getKey($status);
    }

    /**
     * Get the sambe currency pricing with the same currency
     * 
     * @return  Illuminate\Support\Collection
     */
    public function sameCurrencyPricings()
    {
        return self::where('currency', $this->attributes['currency'])
            ->where('priceable_type', $this->attributes['priceable_type'])
            ->where('priceable_id', $this->attributes['priceable_id'])
            ->get();
    }
}