<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\ServerRepository;

class ServerController extends Controller
{
    /**
     * Server Repository Class Container
     * 
     * @var \App\Repository\ServerRepository
     */
    private $server;

    /**
     * Controller constructor methos
     * 
     * @param \App\Repository\ServerRepository $seb
     * @return void
     */
    public function __construct(ServerRepository $server)
    {
    	$this->server = $server;
    }

    /**
     * Populate with available servers
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function servers()
    {
    	$servers = $this->server->all();
        $servers = $this->server->paginate();
    	return response()->json(['servers' => $servers]);
    }
}
