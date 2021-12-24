<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

use App\Observers\SebPaymentObserver;
use App\Enums\Payment\Seb\SebPaymentState as PaymentState;

class SebPayment extends Model
{
    use HasFactory;
    
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'seb_payments';

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
        'payment_id',
        'order_reference',
        'state',
        'amount',
        'billing_address',
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
    	self::observe(SebPaymentObserver::class);
    }

    /**
     * Create callable attribute of "billing_address"
     * This callable attribute will return billing address as array
     * 
     * @return array
     */
    public function getBillingAddressAttribute()
    {
        $address = $this->attributes['billing_address'];
        return json_decode($address, true);
    }

    /**
     * Create settable attribute of "billing_address"
     * This settable attribute will set the billing address colomn of JSON
     * using the supplied data of array
     * 
     * @param array  $address
     * @return void
     */
    public function setBillingAddressAttribute(array $address)
    {
        $this->attributes['billing_address'] = json_encode($address, true);
    }

    /**
     * Create callable attribute of "state_description"
     * This callable attribute will return payment state enum description
     * 
     * @return string
     */
    public function getStateDescriptionAttribute()
    {
        $state = $this->attributes['state'];
        return PaymentState::getDescription($state);
    }

    /**
     * Create settable attribute of "payment_state"
     * This settable attribute will set the payment state value using either
     * integer or string
     * 
     * @param  mixed  $state
     * @return void
     */
    public function setPaymentStateAttribute($state)
    {
        if (is_numeric($state)) {
            $state = (int) $state;
            $state = PaymentState::fromValue($state);
        }

        if (is_string($state)) {
            $state = str_pascal_case($state);
            $state = PaymentState::fromKey($state);
        }

        $this->attributes['state'] = $state;
    }

    /**
     * Get payment parent of current payment
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get list of request trials responses
     */
    public function apiResponses()
    {
        return $this->hasMany(SebPaymentApiResponse::class);
    }

    /**
     * Capture response from api response
     * 
     * @param array  $responseData
     * @return bool
     */
    public function captureResponse(array $responseData)
    {
        $response = new SebPaymentApiResponse();
        $response->response_array = $responseData;
        $response->seb_payment_id = $this->attributes['id'];

        return $response->save();
    }
}