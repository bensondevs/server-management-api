<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'order_items';

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
        'order_id',
        'itemable_type',
        'itemable_id',
        'quantity',
        'currency',
        'price',
        'discount',
        'total',
        'note',
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

    	self::creating(function ($orderItem) {
            $orderItem->id = Uuid::generate()->string;
            $orderItem->calculateTotal();
    	});
    }

    /**
     * Get order (parent) of current item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Itemable model, current possible model
     *  \App\Models\ServicePlan
     *  \App\Models\ServiceAddon
     */
    public function itemable()
    {
        return $this->morphTo();
    }

    /**
     * Calculate total of the order item
     * 
     * @return double
     */
    public function calculateTotal()
    {
        $quantity = $this->attributes['quantity'];
        $price = $this->attributes['price'];

        $total = $quantity * $price - $this->attributes['discount'];
        return $this->attributes['total'] = $total;
    }
}