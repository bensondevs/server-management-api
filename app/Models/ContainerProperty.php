<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Models\ContainerAdditionalProperty as AdditionalProp;
use App\Observers\ContainerPropertyObserver as Observer;
use App\Enums\ContainerProperty\{
    ContainerPropertyType as PropertyType
};

class ContainerProperty extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'container_properties';

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
        'container_id',
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
     * Create callable method of "of(Container $container)"
     * This method will add query to result for the property of
     * a supplied container
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOf(Builder $query, Container $container)
    {
        return $query->where('container_id', $container->id);
    }

    /**
     * Create callable method of "withType(int $propertyType)"
     * This callable method will only add query to result which
     * has `property_type` of specified input
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $propertyType
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithType(Builder $query, int $propertyType)
    {
        return $query->where('property_type', $propertyType);
    }

    /**
     * Get container that owns the current property
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get additionals to this container property
     */
    public function additionals()
    {
        return $this->hasMany(AdditionalProp::class);
    }

    /**
     * Add additional value for current container property
     * 
     * @param  mixed  $value
     * @return \App\Models\ContainerAdditionalProperty
     */
    public function addAdditional($value)
    {
        return AdditionalProp::create([
            'container_id' => $this->attributes['container_id'],
            'container_property_id' => $this->attributes['id'],
            'property_type' => $this->attributes['property_type'],
            'value' => $value,
        ]);
    }
}