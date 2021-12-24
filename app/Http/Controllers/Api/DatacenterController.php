<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

use App\Models\Datacenter;
use App\Http\Resources\DatacenterResource;
use App\Repositories\DatacenterRepository;

class DatacenterController extends Controller
{
    /**
     * Datacenter Repository Class Container
     * 
     * @var \App\Repositories\DatacenterRepository
     */
    private $datacenter;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\DatacenterRepository  $datacenter
     * @return void
     */
    public function __construct(DatacenterRepository $datacenter)
    {
    	$this->datacenter = $datacenter;
    }

    /**
     * Populate available datacenters
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function datacenters()
    {
    	$datacenters = QueryBuilder::for(Datacenter::class)
            ->allowedFilters([
                'datacenter_name', 
                'client_datacenter_name',
                'location'
            ])->allowedSorts(['location'])
            ->allowedIncludes([
                'region', 
                'servers', 
                'subnets'
            ])->allowedAppends(['status_description'])
            ->get();
        $datacenters = DatacenterResource::collection($datacenters);

    	return response()->json(['datacenters' => $datacenters]);
    }

    /**
     * Show datacenter data
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Datacenter $datacenter)
    {
        $datacenter = new DatacenterResource($datacenter);
        return response()->json(['datacenter' => $datacenter]);
    }
}