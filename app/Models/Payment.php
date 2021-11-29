<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Observers\PaymentObserver;
use App\Enums\Payment\{
    PaymentStatus as Status, 
    PaymentMethod as Method
};

class Payment extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'payments';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Model timestamp enability
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Model enable primary key incrementing
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Model query allowed filters for search
     * 
     * @var array
     */
    const ALLOWED_FILTERS = [
        'methods', 
        'status'
    ];

    /**
     * Model fillable columns
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_id',

        'payment_reference',

        'methods',
        'amount',
        'status',
    ];

    /**
     * Columns that should be casted
     * 
     * @var array
     */
    protected $casts = [
        'billing_address' => 'array',
        'vendor_api_response' => 'array',
    ];

    /**
     * Model event handler method
     * 
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(PaymentObserver::class);

    	self::creating(function ($payment) {
            $payment->id = Uuid::generate()->string;
    	});
    }
    public function setVendorApiResponseAttribute(array $response)
    {
        if (isset($response['payment_state'])) {
            if ($response['payment_state'] == 'settled') {
                $this->attributes['status'] = 'paid';
            }
        }

        $json = json_encode($response);
        $this->attributes['vendor_api_response'] = $json;
    }

    public function getVendorApiResponseAttribute()
    {
        $json = $this->attributes['vendor_api_response'];
        $vendorApiResponse = json_decode($json, true);

        return $vendorApiResponse;
    }

    public function setBillingAddressAttribute($address)
    {
        $json = json_encode($address);
        $this->attributes['billing_address'] = $json;
    }

    public function getBillingAddressAttribute()
    {
        $json = $this->attributes['billing_address'];
        $billingAddress = json_decode($json, true);

        return $billingAddress;
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    public function getMethodsDescriptionAttribute()
    {
        $method = $this->attributes['methods'];
        return Method::getDescription($method);
    }

    public function getFormattedAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        return currency_format($amount);
    }

    public function syncPaymentState()
    {
        $apiResponse = $this->attributes['vendor_api_response']; 
        if (! $response = json_decode($apiResponse, true)) {
            $this->attributes['status'] = Status::Unpaid;
            return $this->save();
        }

        $state = $response['payment_state'];
        if ($state == 'settled') {
            $this->attributes['status'] = Status::Settled;
        } else if ($state == 'failed') {
            $this->attributes['status'] = Status::Failed;
        } else {
            $this->attributes['status'] = Status::Unpaid;
        }

        return $this->save();
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findOrder(Order $order)
    {
        return self::where('order_id', $order->id)->first();
    }
}