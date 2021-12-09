<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Webpatser\Uuid\Uuid;

use App\Enums\PrecreatedContainer\PrecreatedContainerStatus as Status;

class PrecreatedContainer extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'precreated_containers';

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
        'status',
        'precreated_container_data',
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

    	self::creating(function ($precreatedContainer) {
            $precreatedContainer->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable function of "waiting()"
     * This callable function will return only pre-created container
     * which has status of waiting
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaiting(Builder $query)
    {
        return $query->where('status', Status::Waiting);
    }

    /**
     * Get order of the pre-created container
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Prepare the pre-created container
     * 
     * @param array  $precreatedContainerData
     */
    public function prepare(array $precreatedContainerData)
    {
        $jsonData = json_encode($precreatedContainerData);

        $this->attributes['precreated_container_data'] = $jsonData;
        $this->attributes['status'] = Status::Prepared;
        $this->attributes['prepared_at'] = now();
        return $this->save();
    }

    /**
     * Set status to be ready to be created
     * 
     * @return bool
     */
    public function setReady()
    {
        $this->attributes['status'] = Status::Ready;
        return $this->save();
    }

    /**
     * Deliver to waiting list
     * 
     * @param string  $waitReason
     * @return bool
     */
    public function wait(string $waitReason = '')
    {
        $this->attributes['reason_waiting'] = $waitReason;
        $this->attributes['status'] = Status::Waiting;
        $this->attributes['waiting_since'] = now();
        return $this->save();
    }

    /**
     * Get the longest waiting pre-created container.
     * 
     * @return self|null
     */
    public static function firstWait()
    {
        return self::waiting()->orderBy('waiting_since')->first();
    }
}