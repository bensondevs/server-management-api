<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\{ 
    ServicePlanRepository, 
    ServiceAddonRepository 
};
use App\Http\Resources\ServicePlanResource;

class ServicePlanController extends Controller
{
    /**
     * Service plan repository class container
     * 
     * @var \App\Repositories\ServicePlanRepository
     */
    private $plan;

    /**
     * Service addon repository class container
     * 
     * @var \App\Repositories\ServiceAddonRepository
     */
    private $addon;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ServicePlanRepository
     * @param \App\Repositories\ServiceAddonRepository
     * @return void
     */
    public function __construct(
    	ServicePlanRepository $servicePlanRepository,
    	ServiceAddonRepository $serviceAddonRepository
    )
    {
    	$this->plan = $servicePlanRepository;
    	$this->addon = $serviceAddonRepository;
    }

    /**
     * Populate available service plans
     * 
     * @return \Illuminate\Support\Facades\Response 
     */
    public function servicePlans()
    {
        $plans = ServicePlan::all();
        $plans = ServicePlanResource::collection($plans);
    	return response()->json(['plans' => $plans]);
    }

    /**
     * Show selected service plan
     * 
     * @param \App\Models\ServicePlan  $plan
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(ServicePlan $plan)
    {
        return response()->json([
            'selected_plan' => new ServicePlanResource($plan)
        ]);
    }
}
