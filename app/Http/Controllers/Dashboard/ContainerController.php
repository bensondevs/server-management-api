<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\SaveContainerRequest as SaveRequest;
use App\Http\Requests\Containers\FindContainerRequest as FindRequest;
use App\Http\Requests\Containers\PopulateContainersRequest as PopulateRequest;

use App\Http\Resources\ContainerResource;

use App\Models\Container;

use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ServerRepository;
use App\Repositories\SubnetRepository;
use App\Repositories\ContainerRepository;
use App\Repositories\ServicePlanRepository;
use App\Repositories\NotificationRepository;

class ContainerController extends Controller
{
    protected $user;
    protected $plan;
    protected $order;
    protected $server;
    protected $subnet;
    protected $container;

    public function __construct(
        UserRepository $userRepository,
        OrderRepository $orderRepository,
        ServerRepository $serverRepository,
        SubnetRepository $subnetRepository,
    	ContainerRepository $containerRepository,
        ServicePlanRepository $servicePlanRepository
    )
    {
        $this->user = $userRepository;
        $this->order = $orderRepository;
        $this->server = $serverRepository;
        $this->subnet = $subnetRepository;
        $this->plan = $servicePlanRepository;
    	$this->container = $containerRepository;
    }

    public function index(PopulateRequest $request)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $containers = $this->container->all($options, true);
            $containers = ContainerResource::apiCollection($containers);

            return response()->json(['containers' => $containers]);
        }

    	return view('dashboard.containers.index');
    }

    public function create()
    {
        $orders = $this->order->containerless();
        $plans = $this->plan->all();
        $customers = $this->user->all();
        $servers = $this->server->all();
        $subnets = $this->subnet->active();

        $variables = ['orders', 'plans', 'customers', 'servers', 'subnets'];
    	return view('dashboard.containers.create', compact($variables));
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $container = $this->container->save($input);

        // Create Container on server
        $servicePlanId = $request->input('service_plan_id');
        $this->container->createOnServer($servicePlanId);
        $this->container->installSystem();

        flash_repository($this->container);

        return redirect()->route('dashboard.containers.index');
    }

    public function view(Container $container, Request $request)
    {
        $container = new ContainerResource($container);
        return $request->ajax() ?
            response()->json(['container' => $container]) :
            view('dashboard.containers.view', compact('container'));
    }

    public function commandExecutions(Container $container, Request $request)
    {
        $from = $request->from_date ?: now()->startOfCentury();
        $till = $request->till_date ?: now()->endOfCentury();

        $this->container->setModel($container);
        $executions = $this->container->commandExecutions($from, $till);

        $data = ['executions' => $executions];
        return $request->ajax() ?
            response()->json($data) :
            view('dashboard.containers.command_executions', $data);
    }

    public function edit(Container $container)
    {
        $orders = $this->order->containerless();
        $plans = $this->plan->all();
        $customers = $this->user->all();
        $servers = $this->server->all();
        $subnets = $this->subnet->active();

        $variables = ['orders', 'plans', 'customers', 'servers', 'subnets', 'container'];
    	return view('dashboard.containers.edit', compact($variables));
    }

    public function update(SaveRequest $request, Container $container)
    {
        $input = $request->validated();

        $container = $this->container->setModel($container);
        $container = $this->container->save($input);

        flash_repository($this->container);

        return redirect()->route('dashboard.containers.index');
    }

    public function recreateOnServer(Container $container)
    {
        $this->container->setModel($container);
        $this->container->createOnServer();

        return apiResponse($this->container);
    }

    public function installSystem(Container $container)
    {
        $this->container->setModel($container);
        $this->container->installSystem();

        return apiResponse($this->container);
    }

    public function delete(Container $container)
    {
        return view('dashboard.containers.delete', compact('container'));
    }

    public function destroy(Request $request, Container $container)
    {
    	$this->container->setModel($container);
        $this->container->destroyInServer();

        if ($request->direct_destroy) {
        	$this->container->delete();
        }

        return redirect()->route('dashboard.containers.index');
    }
}