<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\JobTracker\JobTrackerStatus;

class JobTracker extends Model
{
    protected $table = 'job_trackers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'model_type',
        'model_id',
        'job_class',
        'status',
        'return_response',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($jobTracker) {
            $jobTracker->id = Uuid::generate()->string;
    	});
    }

    public function scopeOfContainer(Builder $query, Container $container)
    {
        return $query->where('model_type', Container::class)
            ->where('model_id', $container->id);
    }

    public function scopeFrom(Builder $query, $from)
    {
        return $query->where('created_at', '>=', $from);
    }

    public function scopeTill(Builder $query, $till)
    {
        return $query->where('created_at', '<=', $till);
    }

    public function setReturnResponseAttribute($response)
    {
        if (is_array($response)) {
            $response = json_encode($response);
        }

        return $this->attributes['return_response'] = $response;
    }

    public function model()
    {
        $model = $this->attributes['model_type'];
        $id = $this->attributes['model_id'];
        return call_user_func($model . '::findOrFail', $id);
    }

    public function job(array $parameters)
    {
        $job = $this->attributes['job_class'];

        return new $job(...$parameters);
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return JobTrackerStatus::getDescription($status);
    }
}