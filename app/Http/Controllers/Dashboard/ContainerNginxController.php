<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\FindContainerRequest as FindRequest;
use App\Http\Requests\Containers\Nginx\CreateLocationRequest;
use App\Http\Requests\Containers\Nginx\RemoveLocationRequest;

use App\Models\Container;

use App\Repositories\ContainerNginxRepository;

class ContainerNginxController extends Controller
{
    private $containerNginx;

    public function __construct(ContainerNginxRepository $containerNginx)
    {
        $this->containerNginx = $containerNginx;
    }

    public function checkStatus(Container $container)
    {
        $this->authorize('check-status-container-nginx', $container);
        $this->containerNginx->setModel($container);

        $status = $this->containerNginx->checkStatus();
        return apiResponse($this->containerNginx, ['nginx_status' => $status]);
    }

    public function checkPidNumbers(Container $container)
    {
        $this->authorize('check-pid-numbers-container-nginx', $container);
        $this->containerNginx->setModel($container);

        $pidNumbers = $this->containerNginx->checkPidNumbers();
        return apiResponse($this->containerNginx, ['pid_numbers' => $pidNumbers]);
    }

    public function createLocation(Container $container, CreateLocationRequest $request)
    {
        $this->containerNginx->setModel($container);

        $location = $request->input('location');
        $this->containerNginx->createLocation($location);

        return apiResponse($this->containerNginx);
    }

    public function removeLocation(RemoveLocationRequest $request)
    {
        $container = $request->getServerContainer();
        $this->containerNginx->setModel($container);

        $location = $request->input('location');
        $this->containerNginx->removeLocation($location);

        return apiResponse($this->containerNginx);
    }

    public function start(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->authorize('start-container-nginx', $container);
        $this->containerNginx->setModel($container);
        $this->containerNginx->start();

        return apiResponse($this->containerNginx);
    }

    public function reload(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->authorize('reload-container-nginx', $container);
        $this->containerNginx->setModel($container);
        $this->containerNginx->reload();

        return apiResponse($this->containerNginx);
    }

    public function restart(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->authorize('restart-container-nginx', $container);
        $this->containerNginx->setModel($container);
        $this->containerNginx->restart();

        return apiResponse($this->containerNginx);
    }

    public function stop(FindRequest $request)
    {
        $container = $request->getServerContainer();
        $this->authorize('stop-container-nginx', $container);
        $this->containerNginx->setModel($container);
        $this->containerNginx->stop();

        return apiResponse($this->containerNginx);
    }
}
