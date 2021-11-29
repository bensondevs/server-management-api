<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Datacenter;

use App\Http\Requests\Datacenters\SaveDatacenterRequest as SaveRequest;
use App\Http\Requests\Datacenters\FindDatacenterRequest as FindRequest;
use App\Http\Requests\Datacenters\PopulateDatacentersRequest as PopulateRequest;
use App\Http\Requests\Datacenters\PopulateDatacenterSubnetsRequest as PopulateSubnetRequest;

use App\Http\Resources\Subnet\SubnetResource;
use App\Http\Resources\DatacenterResource;

use App\Repositories\RegionRepository;
use App\Repositories\SubnetRepository;
use App\Repositories\DatacenterRepository;

class DatacenterController extends Controller
{
    protected $region;
    protected $subnet;
    protected $datacenter;
    protected $notification;

    public function __construct(DatacenterRepository $datacenter, RegionRepository $region, SubnetRepository $subnet)
    {
        $this->region = $region;
        $this->subnet = $subnet;
    	$this->datacenter = $datacenter;
    }

    public function index(PopulateRequest $request)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $datacenters = $this->datacenter->all($options, true);
            $datacenters = DatacenterResource::apiCollection($datacenters);

            return response()->json(['datacenters' => $datacenters]);
        }

    	return view('dashboard.datacenters.index');
    }

    public function create()
    {
        $regions = $this->region->all();

    	return view('dashboard.datacenters.create', compact('regions'));
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated();
        $datacenter = $this->datacenter->save($input);

        flash_repository($this->datacenter);

        return redirect()->route('dashboard.datacenters.index');
    }

    public function edit(Datacenter $datacenter)
    {   
        $regions = $this->region->all();

        $variables = ['datacenter', 'regions'];
        return view('dashboard.datacenters.edit', compact($variables));
    }

    public function switchStatus(Datacenter $datacenter)
    {
    	$datacenter = $this->datacenter->setModel($datacenter);
    	$datacenter = $this->datacenter->switchStatus();
        $datacenter = new DatacenterResource($datacenter);

    	return apiResponse($this->datacenter, $datacenter);
    }

    public function update(SaveDatacenterRequest $request, Datacenter $datacenter)
    {

        $input = $request->validated();
    	$this->datacenter->setModel($datacenter);
    	$this->datacenter->save($input);
        
        flash_repository($this->datacenter);

    	return redirect()->route('dashboard.datacenters.index');
    }

    public function delete(Datacenter $datacenter)
    {
        return view('dashboard.datacenters.delete', compact('datacenter'));
    }

    public function destroy(Datacenter $request)
    {
        $this->datacenter->setModel($datacenter);
        $this->datacenter->delete();

        flash_repository($this->datacenter);

        return redirect()->route('dashboard.datacenters.index');
    }
}