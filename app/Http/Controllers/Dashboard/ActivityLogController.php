<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Repositories\ActivityLogRepository;

class ActivityLogController extends Controller
{
    /**
     * Repository class container
     * 
     * @var  \App\Repositories\ActivityLogRepository|null
     */
    private $log;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\ActivityLogRepository  $log
     * @return void
     */
    public function __construct(ActivityLogRepository $log)
    {
    	$this->log = $log;
    }

    /**
     * Activity log of the application
     * 
     * @param  Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\View|Response
     */
    public function index(Request $request)
    {
        $activities = Activity::all();
    	return $request->ajax() ?
            response()->json(['activities' => $activities]) :
            view('dashboard.activities.index', compact('activities'));
    }

    /**
     * Show activity in deeper detail
     * 
     * @param  \App\Models\Activity  $activity
     * @return Illuminate\Support\Facades\View|Response
     */
    public function show(Activity $activity)
    {
        return request()->ajax() ?
            response()->json(['activity' => $activity]) :
            view('dashboard.activities.show', compact('activity'));
    }
}
