<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class WaitingContainer extends Model
{
    protected $table = 'waiting_containers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'duration_days',
        'waiting_since',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($waitingContainer) {
            $waitingContainer->id = Uuid::generate()->string;
    	});
    }

    public static function findOrder($orderId)
    {
        return self::where('order_id', $orderId)->first();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pushToQueue()
    {
        $order = $this->order()->first();
        $order->process();
    }

    public static function pushBack()
    {
        $waitingContainers = self::with('order')->get();

        foreach ($waitingContainers as $waitingContainer) {
            $waitingContainer->pushToQueue();
        }
    }
}