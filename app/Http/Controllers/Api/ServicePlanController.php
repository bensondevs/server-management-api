<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ServicePlanResource;

class ServicePlanController extends Controller
{
    /**
     * Populate available service plans
     * 
     * @return \Illuminate\Support\Facades\Response 
     */
    public function servicePlans()
    {
        $plans = ServicePlan::all();
        $plans = ServicePlanResource::collection($plans);
    	return response()->json(['service_plans' => $plans]);
    }

    /**
     * Show selected service plan
     * 
     * @param  \App\Models\ServicePlan  $plan
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(ServicePlan $plan)
    {
        $plan = new ServicePlanResource($plan);
        return response()->json(['service_plan' => $plan]);
    }
}
