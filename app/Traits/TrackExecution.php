<?php

namespace App\Traits;

use App\Enums\JobTracker\JobTrackerStatus;

use App\Models\JobTracker;

trait TrackExecution 
{
    private $trackId;

    public function setTracker($track)
    {
        $this->trackId = $track->id;
    }

    public function recordSuccess($message, $response = null)
    {
        if ($inQueueJob = JobTracker::find($this->trackId)) {
            $inQueueJob->status = JobTrackerStatus::Success;
            $inQueueJob->return_response = $response;
            $inQueueJob->response_received_at = carbon()->now();
            $inQueueJob->save();
        }
    }

    public function recordFailed($message, $response = null)
    {
        if ($inQueueJob = JobTracker::find($this->trackId)) {
            $inQueueJob->status = JobTrackerStatus::Failed;
            $inQueueJob->return_response = $response;
            $inQueueJob->response_received_at = carbon()->now();
            $inQueueJob->save();
        }

        $this->fail();
    }

    public function recordResponse($response, $importantAttributes = null, $successCallback = null, $errorCallback = null)
    {
        if (! isset($response['status'])) {
            $this->recordFailed('Server does not give any response', $response);
        }

        if ($response['status'] != 'success') {
            return $this->recordFailed($response['message'], $response);
        }

        if ($importantAttributes !== null) {
            // Convert to array
            if (! is_array($importantAttributes)) {
                $importantAttributes = [$importantAttributes];
            }

            // Check if expected attribute exist within response array
            foreach ($importantAttributes as $attribute) {
                if (! array_key_exists($attribute, $response)) {
                    return $this->recordFailed('Cannot find the expected attribute of `' . $attribute . '`', $response);
                }
            }
        }

        if ($successCallback) {
            $successCallback();
        }

        if ($errorCallback) {
            $errorCallback();
        }

        $message = isset($response['message']) ?
            $response['message'] : 'Success';
        $this->recordSuccess($message, $response);
    }
}