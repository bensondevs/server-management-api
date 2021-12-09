<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Observers\SebPaymentApiResponseObserver;

class SebPaymentApiResponse extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'seb_payment_api_responses';

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
        'seb_payment_id',
        'requester_ip',
        'response',
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
    	self::observe(SebPaymentApiResponseObserver::class);
    }

    /**
     * Create settable attribute of "response_array" attribute
     * This settable attribute will set the column value of "response"
     * with supplied argument of array and put into it as JSON string
     * 
     * @param array  $response
     * @return void
     */
    public function setResponseArrayAttribute(array $response)
    {
        $this->attributes['response'] = json_encode($response, true);
    }

    /**
     * Create callable attribute of "response_array" attribute
     * This callable attribute will return the column value of "response"
     * as array
     * 
     * @return array 
     */
    public function getResponseArrayAttribute()
    {
        $response = $this->attributes['response'];
        return json_decode($response, true);
    }
}