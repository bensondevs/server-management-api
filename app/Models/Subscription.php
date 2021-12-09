<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Enums\Subscription\SubscriptionStatus as Status;

class Subscription extends Model
{
    /**
     * Model table name
     * 
     * @var string
     */
    protected $table = 'subscriptions';

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
        'container_id',
        'status',
        'start',
        'end',
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

    	self::creating(function ($subscription) {
            $subscription->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable attribute of "human_start"
     * This attribute will return the start date for human
     * 
     * @return string
     */
    public function getHumanStartAttribute()
    {
        $start = $this->attributes['start'];
        return carbon()->parse($start)->diffForHumans();
    }

    /**
     * Create callable attribute of "human_end"
     * This attribute will return the end date for human
     * 
     * @return string
     */
    public function getHumanEndAttribute()
    {
        $end = $this->attributes['end'];
        return carbon()->parse($end)->diffForHumans();
    }

    /**
     * Create callable attribute of "grace_period_duration"
     * This attribute will return the duration of grace period
     * 
     * @return int
     */
    public function getGracePeriodDurationAttribute()
    {
        //
    }

    /**
     * Get container of the subscription
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    /**
     * Current subscription services attached
     */
    public function services()
    {
        return $this->hasMany(SubscriptionService::class);
    }
}