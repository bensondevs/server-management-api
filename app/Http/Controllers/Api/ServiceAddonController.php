<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceAddonResource;

use App\Models\ServiceAddon;

class ServiceAddonController extends Controller
{
    /**
     * Populate available service addons
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function serviceAddons()
    {
        $addons = ServiceAddon::all();
        $addons = ServiceAddonResource::collection($addons);

        return response()->json(['addons' => $addons]);
    }

    /**
     * Show selected service addon
     * 
     * @param  \App\Models\ServiceAddon  $adoon
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(ServiceAddon $addon)
    {
        $addon = new ServiceAddonResource($addon);
        return response()->json(['addon' => $addon]);
    }
}
