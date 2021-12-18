<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Webpatser\Uuid\Uuid;

use App\Observers\ServiceAddonObserver as Observer;

use App\Enums\ServiceAddon\ServiceAddonStatus as Status;
use App\Enums\ContainerProperty\ContainerPropertyType as PropertyType;

class ServiceAddon extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'service_addons';

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
        'addon_name',
        'addon_code',
        'description',

        'status',
        'duration_days',

        'property_type',
        'property_value',
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
     * Create callable attribute of "status_description"
     * This callable attribute will return enum description
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable attribute of "property_type_description"
     * This callable attribute will return container property enum
     * description as string.
     * 
     * @return string
     */
    public function getPropertyTypeDescriptionAttribute()
    {
        $type = $this->attributes['property_type'];
        return PropertyType::getDescription($type);
    }

    /**
     * Get pricings for current addon
     */
    public function pricings()
    {
        return $this->morphMany(Pricing::class, 'pricingable');
    }
}