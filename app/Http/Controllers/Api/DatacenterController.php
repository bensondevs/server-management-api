<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
    	$datacenters = $this->datacenter->all();
    	return response()->json([
            'datacenters' => DatacenterResource::collection($datacenters)
        ]);
    }
}
