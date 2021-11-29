<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Traits\TrackInQueue;
use App\Enums\Order\OrderStatus;
use App\Observers\OrderObserver;
use App\Jobs\Order\ProcessOrder;

class Order extends Model
{
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
        'status',
        'customer_id',

        'disk_size',
        
        'vat_size_percentage',
        'total_amount',
    ];

    protected $casts = [
        'meta_container' => 'array',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(OrderObserver::class);

    	self::creating(function ($order) {
            $order->id = isset($order->id) ?
                $order->id : 
                Uuid::generate()->string;
            $order->order_number = $order->generateOrderNumber();
            $order->expired_at = carbon()->now()->addDays(3);
    	});
    }

    public function setMetaContainerAttribute(array $container)
    {
        $this->attributes['meta_container'] = json_encode($container);
    }

    public function getMetaContainerAttribute()
    {
        $json = $this->attributes['meta_container'];

        return json_decode($json, true);
    }

    public function getVatSizePercentageAttribute()
    {
        $vatSize = $this->attributes['vat_size_percentage'];
        return $vatSize . '%';
    }

    public function getVatAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $vatPercentage = $this->attributes['vat_size_percentage'];

        return $amount * ($vatPercentage / 100);
    }

    public function getOrderDateAttribute()
    {
        $orderCreatedAt = $this->attributes['created_at'];

        return carbon($orderCreatedAt)->format('d/m/Y');
    }

    public function getExpiredDateAttribute()
    {
        $orderExpiredAt = $this->attributes['expired_at'];

        return carbon($orderExpiredAt)->format('d/m/Y');
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return OrderStatus::getDescription($status);
    }

    public function generateOrderNumber()
    {
        return (db('orders')->count() + 1);
    }

    public function getRawTotalAttribute()
    {
        // Ordered Plan
        $planAmount = 0;
        if ($orderedPlan = $this->plan) {
            $planAmount = $orderedPlan->countSubTotal();
        }

        return $planAmount;
    }

    public function orderItems()
    {
        $plan = $this->plan;
        $addons = $this->addons()->with(['serviceAddon'])->ge();

        $items = [];
        array_push($items, [
            'item_name' => $plan->servicePlan->plan_name,
            'quantity' => $plan->quantity,
            'unit' => $plan->servicePlan->duration,
            'currency' => $plan->servicePlan->currency,
            'price' => $plan->servicePlan->subscription_fee,
            'sub_total' => $plan->countSubTotal(),
        ]);

        foreach ($addons as $addon) {
            array_push($items, [
                'item_name' => $addon->serviceAddon->addon_name,
                'quantity' => $addon->quantity,
                'unit' => $addon->serviceAddon->quantity_unit,
                'currency' => $addon->serviceAddon->currency,
                'price' => $addon->serviceAddon->subscription_fee,
                'sub_total' => $addon->countSubTotal(),
            ]);
        }

        return $items;
    }

    public function countTotal()
    {
        // Set amount
        $amount = $this->getRawTotalAttribute();
        $this->attributes['amount'] = $amount;
        
        // Amount with VAT percentage
        $vatAmount = $this->getVatAmountAttribute();
        $totalAmount = $amount + $vatAmount;
        return $this->attributes['total_amount'] = $totalAmount;
    }

    public function container()
    {
        return $this->hasOne(Container::class);
    }

    public function customer()
    {
        return $this->hasOne('App\Models\User', 'id', 'customer_id');
    }

    public function plan()
    {
        return $this->hasOne(OrderPlan::class);
    }

    public function servicePlan()
    {
        return $this->hasOneThrough(ServicePlan::class, OrderPlan::class);
    }

    public function addons()
    {
        return $this->hasMany(OrderAddon::class);
    }

    public function serviceAddons()
    {
        return $this->hasManyThrough(ServiceAddon::class, OrderAddon::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function process()
    {
        $order = $this;
        $job = new ProcessOrder($this);
        $order->trackDispatch($job);
    }

    public function reprocess()
    {
        $this->process();
    }

    public function activate()
    {
        $this->attributes['status'] = OrderStatus::Activated;
        $this->save();
    }

    public function createPayment()
    {
        $payment = new Payment();
        $payment->user_id = $this->attributes['customer_id'];
        $payment->order_id = $this->attributes['id'];
        $payment->amount = $this->attributes['total_amount'];
        $payment->save();

        return $payment;
    }
}