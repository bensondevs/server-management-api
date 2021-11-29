<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class OrderAddon extends Model
{
    protected $table = 'order_addons';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'service_addon_id',
        'quantity',
        'currency',
        'current_fee',
        'amount',
        'note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($orderAddon) {
            $orderAddon->id = Uuid::generate()->string;
    	});
    }

    public function countSubTotal()
    {
        return number_format($this->attributes['amount'], 2);
    }

    public function order()
    {
        return $this->belongsTo(
            'App\Models\Order',
            'order_id',
            'id'
        );
    }

    public function serviceAddon()
    {
        return $this->hasOne(
            'App\Models\ServiceAddon', 
            'id',
            'service_addon_id'
        );
    }
}