<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Observers\OrderObserver;
use App\Traits\TrackInQueue;

use App\Enums\Order\OrderStatus as Status;

class Order extends Model
{
    use HasFactory;
    use TrackInQueue;

    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'orders';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Enable timestamp for model execution
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'order_number',
        'status',
        
        'user_id',
        
        'currency',
        'total',
        'vat_size_percentage',
        'grand_total',
    ];

    /**
     * Model event handler function
     * 
     * @return void 
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(OrderObserver::class);
    }

    /**
     * Get VAT Size Percentage with (%)
     * 
     * @return string
     */
    public function getVatSizePercentagePercentAttribute()
    {
        $vatSize = $this->attributes['vat_size_percentage'];
        return $vatSize . '%';
    }

    /**
     * Get amount of money in VAT
     * 
     * @return float
     */
    public function getVatAmountAttribute()
    {
        $total = $this->attributes['total'];
        $vatPercentage = $this->attributes['vat_size_percentage'];

        return $total * ($vatPercentage / 100);
    }

    /**
     * Create callable attribute of `status_description`
     * This attribute will return the enum description of current status
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Get order items of order
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Alias for "orderItems()"
     */
    public function items()
    {
        return $this->orderItems();
    }

    /**
     * Get pre-created container from order
     */
    public function precreatedContainer()
    {
        return $this->hasOne(PrecreatedContainer::class);
    }

    /**
     * Get generated container of order
     */
    public function container()
    {
        return $this->hasOne(Container::class);
    }

    /**
     * User who do the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get payment of the order
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Generate order number from the amount of orders
     * 
     * @return int
     */
    public function generateOrderNumber()
    {
        return (db('orders')->count() + 1);
    }

    /**
     * Calculate total from the sum of order items
     * 
     * @return double
     */
    public function calculateItemsTotal()
    {
        return $this->items()->sum('total');
    }

    /**
     * Count grand total from the order items and VAT
     * 
     * @return double
     */
    public function countGrandTotal()
    {
        $total = $this->calculateItemsTotal();
        $this->attributes['total'] = $total;
        
        $vatAmount = $this->getVatAmountAttribute();
        $grandTotal = $total + $vatAmount;
        return $this->attributes['grand_total'] = $grandTotal;
    }

    /**
     * Create payment for the order
     * 
     * @return \App\Models\Payment
     */
    public function createPayment()
    {
        $payment = new Payment();
        $payment->user_id = $this->attributes['user_id'];
        $payment->amount = $this->attributes['grand_total'];
        $payment->paymentable = $this;
        $payment->save();

        return $payment;
    }

    /**
     * Set order status as paid
     * 
     * @return bool
     */
    public function setPaid()
    {
        $this->attributes['status'] = Status::Paid;
        $this->attributes['paid_at'] = now();
        return $this->save();
    }

    /**
     * Set order status as expired
     * 
     * @return bool
     */
    public function setExpired()
    {
        $this->attributes['status'] = Status::Expired;
        $this->attributes['expired_at'] = now();
        return $this->save();
    }

    /**
     * Add item to order
     * 
     * @param  array  $itemData
     * @return self
     */
    public function addItem(array $itemData)
    {
        //
    }
}