<?php

namespace App\Traits;

use App\Models\JobTracker;

trait TrackInQueue 
{
    public function trackDispatch($queueClass)
    {
        $track = JobTracker::create([
            'model_type' => get_class($this),
            'model_id' => $this->attributes['id'],
            'job_class' => get_class($queueClass),
        ]);

        $queueClass->setTracker($track);
        dispatch($queueClass);
    }

    public function queuedJobs()
    {
        return $this->hasMany(JobTracker::class, 'model_id');
    }
}