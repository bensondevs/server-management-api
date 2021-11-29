<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Regions\FindRegionRequest;
use App\Http\Requests\Regions\SaveRegionRequest;

use App\Models\Region;
use App\Repositories\RegionRepository;

class RegionController extends Controller
{
    protected $region;

    public function __construct(RegionRepository $regionRepository)
    {
    	$this->region = $regionRepository;
    }

    public function index(Request $request)
    {
    	$regions = $this->region->all();

        if ($request->ajax()) {
            return response()->json(['regions' => $regions]);
        }

    	return view('dashboard.regions.index', compact(['regions']));
    }

    public function create()
    {
    	return view('dashboard.regions.create');
    }

    public function store(SaveRegionRequest $request)
    {
        $input = $request->onlyInRules();
        $this->region->save($input);
  
        flash_repository($this->region);

        return redirect()->route('dashboard.regions.index');
    }

    public function edit(Region $region)
    {
    	return view('dashboard.regions.edit', compact('region'));
    }

    public function update(SaveRegionRequest $request, Region $region)
    {
    	$this->region->setModel($region);
    	
        $input = $request->validated();
        $this->region->save($input);

    	flash_repository($this->region);

    	return redirect()->route('dashboard.regions.index');
    }

    public function delete(Region $region)
    {
        return view('dashboard.regions.delete', compact('region'));
    }

    public function destroy(Region $region)
    {   
        $this->region->setModel($region);
        $this->region->delete();

        flash_repository($this->region);

        return redirect()->route('dashboard.regions.index');
    }
}
