<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Http\Resources\CountryResource;

class CountryController extends Controller
{
    /**
     * Collect all countries
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function countries()
    {
        $countries = Country::orderBy('country_name')->get();
        return response()->json([
            'countries' => CountryResource::collection($countries)
        ]);
    }
}
