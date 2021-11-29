<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class ServiceAddon extends Model
{
    protected $table = 'service_addons';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'addon_name',
        'addon_type',
        'currency',
        'addon_fee',
        'quantity',
        'unit',
        'description',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($serviceAddon) {
            $serviceAddon->id = Uuid::generate()->string;
    	});
    }

    public function getQuantityUnitAttribute()
    {
        $quantity = $this->attributes['quantity'];
        $unit = $this->attributes['unit'];
        return $quantity . ' ' . $unit;
    }
}