<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Region;
use App\Http\Resources\RegionResource;

class RegionController extends Controller
{
    /**
     * Populate available regions
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function regions()
    {
        $regions = RegionResource::collection(Region::all());
    	return response()->json(['regions' => $regions]);
    }
}
