<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

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
        $regions = Region::all();
        $regions = RegionResource::collection($regions);
    	return response()->json(['regions' => $regions]);
    }
}
