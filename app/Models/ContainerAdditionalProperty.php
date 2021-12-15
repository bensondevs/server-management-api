<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\ContainerAdditionalPropertyObserver as Observer;

class ContainerAdditionalProperty extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'container_additional_properties';

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
        'container_property_id',
        'additional_value',
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
     * Get container that posses this additional property
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Get container property supported by this additional property
     */
    public function property()
    {
        return $this->belongsTo(ContainerProperty::class);
    }
}