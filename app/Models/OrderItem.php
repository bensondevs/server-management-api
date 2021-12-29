<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\OrderItemObserver as Observer;

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
        self::observe(Observer::class);
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
        $price = isset($this->attributes['price']) ?
            $this->attributes['price'] : 0;
        $discount = isset($this->attributes['discount']) ?
            $this->attributes['discount'] : 0;

        $total = $quantity * $price - $discount;
        return $this->attributes['total'] = $total;
    }

    /**
     * Set order (parent) of current order item.
     * This method will set the `order_id` of current model
     * 
     * @param  \App\Models\Order  $order
     * @return $this
     */
    public function for(Order $order)
    {
        $this->attributes['order_id'] = $order->id;
        return $this;
    }

    /**
     * Set subscription's subscribeable as item
     * for renewal and attach it to current item
     * 
     * @param  \App\Models\Subscription  $subs
     * @param  bool  $saveDirectly
     * @return $this
     */
    public function setSubscription(Subscription $subs, bool $saveDirectly = false)
    {
        // Set all affected and related attributes
        $this->attributes['is_renewal'] = true;
        $this->attributes['subscription_id'] = $subs->id;
        $this->attributes['itemable_type'] = $subs->subscribeable_type;
        $this->attributes['itemable_id'] = $subs->subscribeable_id;

        // If parameter save directly is true
        // This function will execute the save action of model
        // Otherwise, just let it be
        if ($saveDirectly) $this->save();

        return $this;
    }
}