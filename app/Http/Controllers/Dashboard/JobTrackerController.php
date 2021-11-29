<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\JobTrackers\PopulateJobTrackersRequest as PopulateRequest;
use App\Http\Requests\JobTrackers\RetryJobRequest;

use App\Http\Resources\JobTrackerResource;

use App\Repositories\JobTrackerRepository;

class JobTrackerController extends Controller
{
    private $jobTracker;

    public function __construct(JobTrackerRepository $jobTracker)
    {
        $this->jobTracker = $jobTracker;
    }

    public function index(PopulateRequest $request)
    {
        $options = $request->options();

        $trackers = $this->jobTracker->all($options);
        $trackers = $this->jobTracker->paginate();
        $trackers->data = JobTrackerResource::collection($trackers);

        return view('dashboard.job_trackers.index', compact('trackers'));
    }

    public function clear(ClearRequest $request)
    {
        $dateRange = $request->dateRange();
        $this->jobTracker->clear($dateRange);

        return apiResponse($this->jobTracker);
    }

    public function retry(RetryJobRequest $request)
    {
        $jobTracker = $request->getJobTracker();

        $this->jobTracker->setModel($jobTracker);
        $this->jobTracker->retry();

        return apiResponse($this->jobTracker);
    }
}