<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ActivityLogRepository;

class ActivityLogController extends Controller
{
    protected $log;

    public function __construct(ActivityLogRepository $log)
    {
    	$this->log = $log;
    }

    public function populate(Request $request)
    {
    	if ($start = $request->get('start'))
    		$this->log->setStart($start);

    	if ($end = $request->get('end'))
    		$this->log->setEnd($end);

    	$logs = $this->log->allActtivities();

    	return response()->json(['logs' => $logs]);
    }
}
