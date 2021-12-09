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

        'method',
        'amount',
        'status',
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

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return enum description
     * of the column value of status
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable "methods_descrption" attribute
     * This callable attribute will return enum description
     * of the column value of method
     * 
     * @return string
     */
    public function getMethodDescriptionAttribute()
    {
        $method = $this->attributes['method'];
        return Method::getDescription($method);
    }

    /**
     * Create callable "formatted_amount" attribute
     * This callable attribute will return the currency formatted form
     * 
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        return currency_format($amount);
    }

    /**
     * Get the user of the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order paid by this payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Guess what payment method model is attached
     */
    public function vendorPayment()
    {
        switch ($this->attributes['method']) {
            case Method::SEB:
                $model = SebPayment::class;
                break;
            
            case Method::Paypal:
                $model = PaypalPayment::class;
                break;

            case Method::Stripe:
                $model = StripePayment::class;
                break;

            default:
                $model = SebPayment::class;
                break;
        }

        return $this->hasOne($model);
    }

    /**
     * Get seb payment model
     */
    public function sebPayment()
    {
        return $this->hasOne(SebPayment::class);
    }

    /**
     * Get stripe payment model
     */
    public function stripePayment()
    {
        return $this->hasOne(StripePayment::class);
    }

    /**
     * Get Paypal payment model
     */
    public function paypalPayment()
    {
        return $this->hasOne(PaypalPayment::class);
    }
}