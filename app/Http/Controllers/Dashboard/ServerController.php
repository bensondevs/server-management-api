<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Servers\SaveServerRequest;
use App\Http\Requests\Servers\FindServerRequest;

use App\Http\Resources\ServerResource;

use App\Repositories\AmqpRepository;
use App\Repositories\ServerRepository;
use App\Repositories\RabbitMQApiRepository;
use App\Repositories\DatacenterRepository;

class ServerController extends Controller
{
    protected $amqp;
    protected $rabbitmqApi;

    protected $server;
    protected $datacenter;

    public function  __construct(
        AmqpRepository $amqpRepository,
        ServerRepository $serverRepository,
        DatacenterRepository $datacenterRepository
    )
    {
        $this->amqp = $amqpRepository;
    	$this->server = $serverRepository;
        $this->datacenter = $datacenterRepository;

        $this->middleware('permission:view servers')->only(['index', 'populate']);
        $this->middleware('permission:toggle servers')->only(['toggleStatus']);
        $this->middleware('permission:edit servers')->only(['edit', 'update']);
        $this->middleware('permission:delete servers')->only(['confirmDelete', 'delete']);
    }

    public function index()
    {
    	return view('dashboard.servers.index');
    }

    public function create()
    {
        $datacenters = $this->datacenter->all();
        return view('dashboard.servers.create', compact('datacenters'));
    }

    public function containers(FindServerRequest $request)
    {
        $server = $this->server->findWith(
            $request->get('id'),
            ['containers.customer', 'containers.subnetIp']
        );

        return view(
            'dashboard.servers.containers', 
            compact(['server'])
        );
    }

    public function edit(FindServerRequest $request)
    {
        $server = $this->server->find($request->get('id'));
        $datacenters = $this->datacenter->all();

        return view(
            'dashboard.servers.edit', 
            compact(['server', 'datacenters'])
        );
    }

    public function confirmDelete(FindServerRequest $request)
    {
        $server = $this->server->find($request->get('id'));

        activity()
            ->performedOn($this->server->getModel())
            ->causedBy(auth()->user())
            ->log($request->user()->anchorName() . ' had attempted server deletion.');

        return view('dashboard.servers.confirm-delete', compact('server'));
    }

    public function populate()
    {
        $servers = $this->server->allWithData();
        $servers = $this->server->paginate();
        $servers->data = ServerResource::collection($servers);

    	return response()->json(['servers' => $servers]);
    }

    public function store(SaveServerRequest $request)
    {
        /* Create Server */
        $input = $request->onlyInRules();
    	$server = $this->server->save($input);
        
        /* Create AMQP Queue */
        $this->amqp->connectServerQueue($server);

        activity()
            ->performedOn($this->server->getModel())
            ->causedBy(auth()->user())
            ->log(auth()->user()->anchorName() . ' had created a new server.');
        flash_repository($this->server);

    	return redirect()->route('dashboard.servers.index');
    }

    public function toggleStatus(FindServerRequest $request)
    {
        $this->server->find($request->input('id'));
        $this->server->toggleStatus();

        activity()
            ->performedOn($this->server->getModel())
            ->causedBy(auth()->user())
            ->log(auth()->user()->anchorName() . ' had changed a server status.');
        flash_repository($this->server);

        return apiResponse(
            $this->server, 
            $this->server->getModel()
        );
    }

    public function update(SaveServerRequest $request)
    {
    	$server = $this->server->find($request->input('id'));
        $originalName = $server->server_name;
        $originalComplete = $server->complete_server_name;
        
        $input = $request->onlyInRules();
    	$updatedServer = $this->server->save($input);

        // Renew the queue if the server name is different
        

        activity()
            ->performedOn($this->server->getModel())
            ->causedBy(auth()->user())
            ->log(auth()->user()->anchorName() . ' had updated a server.');
        flash_repository($this->server);

    	return redirect()->route('dashboard.servers.index');
    }

    public function delete(FindServerRequest $request)
    {
    	$server = $this->server->find($request->input('id'));
    	$this->server->delete();

        activity()
            ->performedOn($server)
            ->causedBy(auth()->user())
            ->log(auth()->user()->anchorName() . ' had deleted a server.');
        flash_repository($this->server);

    	return redirect()->route('dashboard.servers.index');
    }
}