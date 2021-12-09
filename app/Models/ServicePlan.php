<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ServicePlan extends Model
{
    /**
     * The model table name
     * 
     * @var string
     */
    protected $table = 'service_plans';

    /**
     * The model primary key
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
        'is_hidden',
        'plan_name',
        'plan_code',
        'description',
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

    	self::creating(function ($servicePlan) {
            $servicePlan->id = Uuid::generate()->string;

            if (! $servicePlan->plan_code) {
                $servicePlan->plan_code = random_string(5);
            }
    	});
    }

    /**
     * Get list of plan items
     */
    public function items()
    {
        return $this->hasMany(ServicePlanItem::class);
    }

    /**
     * Get pricing of the plan
     */
    public function pricings()
    {
        return $this->morphMany(Pricing::class, 'pricingable');
    }

    /**
     * Hide service plan from the users
     * 
     * @return bool
     */
    public function hide()
    {
        $this->attributes['is_hidden'] = true;
        return $this->save();
    }

    /**
     * Unhide service plan from the users
     * 
     * @return bool
     */
    public function unhide()
    {
        $this->attributes['is_hidden'] = false;
        return $this->save();
    }
}