<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, Builder, SoftDeletes };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Enums\Subscription\SubscriptionStatus as Status;
use App\Observers\SubscriptionObserver as Observer;

class Subscription extends Model
{
    use HasFactory;

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
        'user_id',

        'subscribeable_type',
        'subscribeable_id',

        'subscriber_type',
        'subscriber_id',

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
        self::observe(Observer::class);
    }

    /**
     * Create callable method of "subscribedBy($subscriber)"
     * This method will query only subscription with subscriber
     * of specified subscriber
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $subscriber
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSubscribedBy(Builder $query, $subscriber)
    {
        $type = get_class($subscriber);
        $id = $subscriber->id;

        return $query->where('subscriber_type', $type)
            ->where('subscriber_id', $id);
    }

    /**
     * Create callable method of "active()"
     * This method will query only subscription with status of active
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('status', Status::Active);
    }

    /**
     * Create callable method of "mainatainable()"
     * This method will query only subscription which is maintainable.
     * The maintainable subscriptions are those subscriptions that
     * is not "Terminated" yet.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaintainable(Builder $query)
    {
        return $query->where('status', '!=', Status::Terminated);
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
        $destroy = $this->attributes['destroy_at'];
        $end = $this->attributes['end'];

        return carbon($end)->diffInDays($destroy);
    }

    /**
     * Get user of the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get subscriber of the subscription
     */
    public function subscriber()
    {
        return $this->morphTo('subscriber');
    }

    /**
     * Current subscription services attached
     */
    public function subscribeable()
    {
        return $this->morphTo('subscribeable');
    }

    /**
     * Check if status is still active
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->attributes['status'] == Status::Active;
    }

    /**
     * Check if status is still grace period
     * 
     * @return bool
     */
    public function isInGracePeriod()
    {
        return $this->attributes['status'] == Status::InGracePeriod;
    }

    /**
     * Check if status is expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        return $this->attributes['status'] == Status::Expired;
    }

    /**
     * Check if the subscription should be ended by now
     * 
     * @return bool
     */
    public function shouldBeEndedNow()
    {
        return now() >= $this->attributes['end'];
    }

    /**
     * Check if the subscription should be expired by now
     * 
     * @return bool
     */
    public function shouldBeExpiredNow()
    {
        return now() >= $this->attributes['expired_at'];
    }

    /**
     * Check if the subscription should be terminated by now
     * 
     * @return bool
     */
    public function shouldBeTerminatedNow()
    {
        return now() >= $this->attributes['terminated_at'];
    }

    /**
     * Terminate subscription and send it to grace period
     * 
     * @return bool
     */
    public function setIntoGracePeriod()
    {
        $this->attributes['status'] = Status::InGracePeriod;
        return $this->save();
    }

    /**
     * Set subscription status to expired.
     * 
     * @return bool
     */
    public function setAsExpired()
    {
        $this->attributes['status'] = Status::Expired;
        return $this->save();
    }

    /**
     * Set subscription status to Terminated
     * 
     * @return bool
     */
    public function setAsTerminated()
    {
        $this->attributes['status'] = Status::Terminated;
        return $this->save();
    }

    /**
     * Terminate the subscription and set as terminated
     * 
     * @return bool
     */
    public function terminate()
    {
        $subscriber = $this->subscriber;
        $subscriber->delete();

        return $this->setAsTerminated();
    }
}