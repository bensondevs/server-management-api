<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Orders\SaveOrderRequest as SaveRequest;
use App\Http\Requests\Orders\FindOrderRequest as FindRequest;
use App\Http\Requests\Orders\PopulateOrdersRequest as PopulateRequest;
use App\Http\Requests\Orders\PlaceOrderRequest as PlaceRequest;

use App\Models\Order;

use App\Http\Resources\OrderResource;

use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\DatacenterRepository;
use App\Repositories\ServicePlanRepository;

class OrderController extends Controller
{
    protected $user;
    protected $plan;
    protected $order;
    protected $datacenter;

    public function __construct(
        UserRepository $userRepository,
    	OrderRepository $orderRepository,
        DatacenterRepository $datacenterRepository,
        ServicePlanRepository $servicePlanRepository
    )
    {
        $this->user = $userRepository;
    	$this->order = $orderRepository;
        $this->datacenter = $datacenterRepository;
        $this->plan = $servicePlanRepository;
    }

    public function index(PopulateRequest $request)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $orders = $this->order->all($options);
            $orders = $this->order->paginate();
            $orders = OrderResource::apiCollection($orders);

            return response()->json(['orders' => $orders]);
        }

    	return view('dashboard.orders.index');
    }

    public function create()
    {
        $customers = $this->user->all();
        $plans = $this->plan->all();
        $datacenters = $this->datacenter->all();

        $options = ['customers', 'plans', 'datacenters'];
    	return view('dashboard.orders.create', compact($options));
    }

    public function view(Request $request, Order $order)
    {
        if ($request->ajax()) {
            $relationships = ['customer', 'container', 'plan.servicePlan', 'addons.serviceAddon'];
            $order->load($relationships);
            $order->vat_amount = $order->vat_amount;
            $order->customer->full_name = $order->customer->full_name;
            $order = new OrderResource($order);

            return response()->json(['order' => $order]);
        }

        return view('dashboard.orders.view', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = $this->user->all();
        $plans = $this->plan->all();
        $datacenters = $this->datacenter->all();

        $options = ['order', 'customers', 'plans', 'datacenters'];
    	return view('dashboard.orders.edit', compact($options));
    }

    public function load(Order $order)
    {
        $relationships = ['customer', 'container', 'plan.servicePlan', 'addons.serviceAddon'];
        $order->load($relationships);
        $order->vat_amount = $order->vat_amount;
        $order->customer->full_name = $order->customer->full_name;

        return response()->json(['order' => new OrderResource($order)]);
    }

    public function store(PlaceRequest $request)
    {
        $input = $request->orderData();
    	$order = $this->order->place($input);

    	flash_repository($this->order);

    	return $request->ajax() ?
            apiResponse($this->order, ['order' => $order->fresh()]) :
            redirect()->route('dashboard.orders.index');
    }

    public function manuallyCreateContainer(Request $request, Order $order)
    {
        $this->order->setModel($order);
        $this->order->process();

        if (! $request->ajax()) {
            flash_repository($this->order);
            return redirect()->back();
        }

        return apiResponse($this->order);
    }

    public function update(SaveRequest $request, Order $order)
    {
        $input = $request->validated();
        
        $this->order->setModel($order);
    	$this->order->save($input);

    	flash_repository($this->order);

    	return $request->ajax() ?
            apiResponse($this->order, compact('order')) :
            redirect()->route('dashboard.orders.index');
    }

    public function delete(Order $order)
    {
        return view('dashboard.orders.delete', compact('order'));
    }

    public function destroy(Order $order)
    {   
    	$this->order->setModel($order);
    	$this->order->delete();

    	flash_repository($this->order);

    	return $request->ajax() ? 
            apiResponse($this->order) : 
            redirect()->route('dashboard.orders.index');
    }
}