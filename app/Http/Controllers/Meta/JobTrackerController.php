<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\JobTracker\JobTrackerStatus;

class JobTrackerController extends Controller
{
    /**
     * Collect all status badge classes for job tracker
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statusBadges()
    {
        $statuses = collect(JobTrackerStatus::asSelectArray());
        $statuses = $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new JobTrackerStatus($value))->badgeHtmlClass(),
            ];
        });

        return response()->json($statuses);
    }
}
