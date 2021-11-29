<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class OrderPlan extends Model
{
    protected $table = 'order_plans';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',

        'service_plan_id',

        'quantity',
        'note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($orderPlan) {
            $orderPlan->id = Uuid::generate()->string;
    	});
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function servicePlan()
    {
        return $this->belongsTo(ServicePlan::class);
    }

    public function countSubTotal()
    {
        $servicePlan = $this->servicePlan;
        $fee = $servicePlan->subscription_fee;
        $quantity = $this->attributes['quantity'];

        return number_format($fee * $quantity, 2);
    }

    public function getDurationDaysAttribute()
    {
        $plan = $this->servicePlan;
        $planDuration = $plan->duration_in_days; 
        $quantity = $this->attributes['quantity'];

        return $planDuration * $quantity;
    }
}