<?php

namespace App\Traits;

use App\Models\JobTracker;

trait TrackInQueue 
{
    /**
     * Track dispatch the job class
     * 
     * @param  mixed  $jobClass
     */
    public function trackDispatch($jobClass)
    {
        $track = JobTracker::create([
            'model_type' => get_class($this),
            'model_id' => $this->attributes['id'],
            'job_class' => get_class($jobClass),
        ]);

        $jobClass->setTracker($track);
        dispatch($jobClass);
    }

    /**
     * Get queued job class record
     */
    public function queuedJobs()
    {
        return $this->hasMany(JobTracker::class, 'model_id');
    }
}