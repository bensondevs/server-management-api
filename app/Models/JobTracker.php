<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{Model, Builder, SoftDeletes};
use Webpatser\Uuid\Uuid;

use App\Enums\JobTracker\JobTrackerStatus;

class JobTracker extends Model
{
    /**
     * Model database table
     * 
     * @var string
     */
    protected $table = 'job_trackers';

    /**
     * Model database primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Enable timestamp for model execution
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
     * Model fillable column
     * 
     * @var array
     */
    protected $fillable = [
        'model_type',
        'model_id',
        'job_class',
        'status',
        'return_response',
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

    	self::creating(function ($jobTracker) {
            $jobTracker->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable function of "ofContainer(Container $container)"
     * This callable function will query only job trackers 
     * for a certain container specified.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Container  $container
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfContainer(Builder $query, Container $container)
    {
        return $query->where('model_type', Container::class)
            ->where('model_id', $container->id);
    }

    /**
     * Create callable function of "from(\DateTime $from)"
     * This callable function will add query to only populate
     * data from certain time specified.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \DateTime  $from
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFrom(Builder $query, \DateTime $from)
    {
        return $query->where('created_at', '>=', $from);
    }

    /**
     * Create callable function of "till(\DateTime $till)"
     * This callable function will add query to only populate
     * data until certain time specified.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \DateTime  $till
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTill(Builder $query, \DateTime $till)
    {
        return $query->where('created_at', '<=', $till);
    }

    /**
     * Create callable function of "between(\DateTime $from, \DateTime $till)"
     * This callable function wll add query to only populate
     * data from certain time until certain time specified
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \DateTime  $from
     * @param  \DateTime  $till
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetween(
        Builder $query, 
        \DateTime $from, 
        \DateTime $till
    ) {
        return $query->where('created_at', '>=', $from)
            ->where('created_at', '<=', $till);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will return status description
     * as string
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return JobTrackerStatus::getDescription($status);
    }

    /**
     * Create settable attribute of "return_response"
     * This settable attribute will set the value of "return_response"
     * column by either using array or JSON string
     * 
     * @param  array|string  $response
     * @return void
     */
    public function setReturnResponseAttribute($response)
    {
        $this->attributes['return_response'] = is_array($response) ?
            json_encode($response) : 
            $response;
    }

    /**
     * Get model tracked by the job tracker
     * 
     * @return mixed
     */
    public function model()
    {
        $model = $this->attributes['model_type'];
        $id = $this->attributes['model_id'];
        return call_user_func($model . '::findOrFail', $id);
    }

    /**
     * Execute the job
     * 
     * @param  array  $parameters
     * @return  mixed
     */
    public function job(array $parameters)
    {
        $job = $this->attributes['job_class'];

        return new $job(...$parameters);
    }
}