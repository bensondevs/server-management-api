<?php

namespace App\Http\Controllers\Api\Container;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Container;

use App\Repositories\ContainerNginxRepository as NginxRepository;

class ContainerNginxController extends Controller
{
    /**
     * NGINX repository class container
     * 
     * @var NginxRepository
     */
    private $nginx;

    /**
     * Controller constructor class
     * 
     * @param NginxRepository  $nginx
     * @return void
     */
    public function __construct(NginxRepository $nginx)
    {
        $this->nginx = $nginx;
    }

    /**
     * Check NGINX condition and return information
     * 
     * @param \App\Models\Container  $container 
     */
    public function containerNginx(Container $container)
    {
        $this->nginx->setModel($container);
        $informations = $this->nginx->completeCheck();
        return response()->json(['nginx_informations' => $informations]);
    }

    /**
     * Start NGINX Service
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function start(Container $container)
    {
        $container = $this->nginx->setModel($container);
        $status = $this->nginx->start();
        return apiResponse($this->nginx, ['nginx_status' => $status]);
    }

    /**
     * Restart NGINX Service
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function restart(Container $container)
    {
        $container = $this->nginx->setModel($container);
        $status = $this->nginx->restart();
        return apiResponse($this->nginx, ['nginx_status' => $status]);
    }

    /**
     * Reload NGINX Service
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function reload(Container $container)
    {
        $container = $this->nginx->setModel($container);
        $status = $this->nginx->reload();
        return apiResponse($this->nginx, ['nginx_status' => $status]);
    }

    /**
     * Stop NGINX Service
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function stop(Container $container)
    {
        $container = $this->nginx->setModel($container);
        $status = $this->nginx->stop();
        return apiResponse($this->nginx, ['nginx_status' => $status]);
    }
}
