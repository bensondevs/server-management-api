<?php

namespace App\Http\Controllers\Api;

use App\Models\Server;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    /**
     * Populate with available servers
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function servers()
    {
    	$servers = QueryBuilder::for(Server::class)
            ->allowedFilters(['server_name', 'status'])
            ->allowedIncludes(['datacenter', 'containers'])
            ->allowedAppends(['full_server_name', 'ip_address'])
            ->allowedSorts(['server_name', 'status'])
            ->get();
        return response()->json([
            'servers' => ServerResource::collection($servers)
        ]);
    }
}