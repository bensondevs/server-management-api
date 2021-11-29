<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ServicePlanResource;
use App\Http\Resources\OrderResource;

use App\Http\Requests\ServicePlans\FindServicePlanRequest;
use App\Http\Requests\ServicePlans\SaveServicePlanRequest;

use App\Models\Currency;
use App\Models\ServicePlan;

use App\Repositories\ServicePlanRepository;

class ServicePlanController extends Controller
{
    protected $plan;

    public function __construct(
    	ServicePlanRepository $servicePlanRepository
    )
    {
    	$this->plan = $servicePlanRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->populate();
        }

    	return view('dashboard.service_plans.index');
    }

    public function populate()
    {
        $plans = $this->plan->all();
        $plans = $this->plan->paginate();
        $plans->data = ServicePlanResource::collection($plans);

        return response()->json(['plans' => $plans]);
    }

    public function create()
    {
        $currencies = Currency::all();

    	return view('dashboard.service_plans.create', compact('currencies'));
    }

    public function store(SaveServicePlanRequest $request)
    {
    	$input = $request->onlyInRules();
    	$this->plan->save($input);

    	flashMessage($this->plan);

    	return redirect()->route('dashboard.service_plans.index');
    }

    public function orders(FindServicePlanRequest $request)
    {
        $plan = $this->plan->find($request->input('id'));
        $orders = $plan->orders;

        return response()->json(['orders' => OrderResource::collection($orders)]);
    }

    public function view(Request $request)
    {
        $plan = $this->plan->find($request->input('id'));
        $plan->load(['pricings']);

        return view('dashboard.service_plans.view', compact('plan'));
    }

    public function edit(Request $request)
    {
    	$plan = $this->plan->find($request->input('id'));
        $currencies = Currency::all();

    	return view(
            'dashboard.service_plans.edit', 
            compact(['plan', 'currencies'])
        );
    }

    public function changeStatus(ChangeServicePlanStatusRequest $request)
    {
        $this->plan->find($request->input('id'));
        $plan = $this->plan->changeStatus(
            $request->input('status')
        );

        return response()->json(['plan' => $plan]);
    }

    public function update(SaveServicePlanRequest $request)
    {
    	$this->plan->find($request->input('id'));
    	$this->plan->save($request->onlyInRules());

    	flashMessage($this->plan);

    	return redirect()->route('dashboard.service_plans.index');
    }

    public function confirmDelete(Request $request)
    {
    	$plan = $this->plan->find($request->input('id'));

    	return view('dashboard.service_plans.confirm-delete', compact('plan'));
    }

    public function delete(Request $request)
    {
    	$this->plan->find($request->input('id'));
    	$this->plan->delete();

        flashMessage($this->plan);

    	return redirect()->route('dashboard.service_plans.index');
    }
}
