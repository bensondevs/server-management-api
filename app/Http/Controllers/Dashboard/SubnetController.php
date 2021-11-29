<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Subnets\SaveSubnetRequest as SaveRequest;
use App\Http\Requests\Subnets\PopulateSubnetsRequest as PopulateRequest;

use App\Http\Resources\SubnetResource;

use App\Models\Subnet;
use App\Models\Datacenter;

use App\Repositories\SubnetRepository;

class SubnetController extends Controller
{
    private $subnet;

    public function __construct(SubnetRepository $subnet)
    {
    	$this->subnet = $subnet;
    }

    public function index(PopulateRequest $request)
    {
        if ($request->ajax()) {
            $options = $request->options();
            $options['withs'][] = 'datacenter';

            $subnets = $this->subnet->allWithCount($options, true);
            $subnets = SubnetResource::apiCollection($subnets);

            return response()->json(['subnets' => $subnets]);
        }

    	return view('dashboard.subnets.index');
    }

    public function datacenterSubnets(PopulateRequest $request, Datacenter $datacenter)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $subnets = $this->subnet->allWithCount($options, true);
            $subnets = SubnetResource::apiCollection($subnets);

            return response()->json(['subnets' => $subnets]);
        }

        return view('dashboard.datacenters.subnets', compact('datacenter'));
    }

    public function create()
    {
    	$datacenters = Datacenter::all();
    	return view('dashboard.subnets.create', compact('datacenters'));
    }

    public function store(SaveRequest $request)
    {
        $input = $request->validated;
        $this->subnet->save($input);

        return redirect()->route('dashboard.subnets.index');
    }

    public function edit(Subnet $subnet)
    {
    	$datacenters = Datacenter::all();

        $variables = ['subnet', 'datacenters'];
    	return view('dashboard.subnets.edit', compact($variables));
    }

    public function update(SaveRequest $request, Subnet $subnet)
    {
        $input = $request->validated();
    	
        $this->subnet->setModel($subnet);
    	$this->subnet->save($input);

    	flash_repository($this->subnet);

    	return redirect()->route('dashboard.subnets.index');
    }

    public function switchStatus(Subnet $subnet)
    {
        $subnet = $this->subnet->setModel($subnet);
        $subnet = $this->subnet->switchStatus();
        $subnet = new SubnetResource($subnet);

        return response()->json(['subnet' => $subnet]);
    }

    public function delete(Subnet $subnet)
    {
        return view('dashboard.subnets.delete', compact('subnet'));
    }

    public function destroy(Subnet $subnet)
    {
    	$this->subnet->setModel($subnet);
    	$this->subnet->destroy();

    	flash_repository($this->subnet);

    	return redirect()->route('dashboard.subnets.index');
    }
}
