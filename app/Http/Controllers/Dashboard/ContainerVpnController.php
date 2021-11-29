<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\Http\Requests\Containers\FindContainerRequest as FindRequest;
use App\Http\Requests\Containers\Vpn\CreateContainerVpnUserRequest as CreateUserRequest;
use App\Http\Requests\Containers\Vpn\RevokeContainerVpnUserRequest as RevokeUserRequest;
use App\Http\Requests\Containers\Vpn\FindVpnUserRequest as FindUserRequest;

use App\Models\VpnUser;
use App\Models\Container;

use App\Repositories\ContainerVpnRepository;

class ContainerVpnController extends Controller
{
    private $containerVpn;

    public function __construct(ContainerVpnRepository $containerVpn)
    {
        $this->containerVpn = $containerVpn;
    }

    public function manage(Container $container)
    {
        $this->containerVpn->setModel($container);

        $pidNumbers = $this->containerVpn->checkPidNumbers();
        $vpnUsers = $this->containerVpn->users();

        $variables = ['container', 'pidNumbers', 'vpnUsers'];
        return view('dashboard.containers.manage.vpn', compact($variables));
    }

    public function inputCreateUser(Container $container)
    {
        return view('dashboard.containers.manage.vpns.create-user', compact('container'));
    }

    public function createUser(CreateUserRequest $request, Container $container)
    {
        $this->containerVpn->setModel($container);

        $username = $request->input('username');
        $this->containerVpn->createUser($username);

        flash_repository($this->containerVpn);

        return redirect()->route('dashboard.containers.vpn.manage', ['container' => $container]);
    }

    public function confirmRevokeUser(Container $container, VpnUser $vpnUser)
    {
        return view('dashboard.containers.manage.vpns.revoke-user', ['vpnUser' => $vpnUser, 'container' => $container]);
    }

    public function revokeUser(RevokeUserRequest $request)
    {
        $vpnUser = $request->getVpnUser();
        $container = $vpnUser->container;
        $this->containerVpn->setModel($container);

        $username = $request->input('username');
        $this->containerVpn->revokeUser($username);

        flash_repository($this->containerVpn);

        return redirect()->route('dashboard.containers.vpn.manage', ['container' => $container]);
    }

    public function checkStatus(Container $container)
    {
        $this->containerVpn->setModel($container);
        $status = $this->containerVpn->checkStatus();

        return apiResponse($this->containerVpn, ['vpn_status' => $status]);
    }

    public function checkPidNumbers(Container $container)
    {
        $this->container->setModel($container);
        $pidNumbers = $this->containerVpn->checkPidNumbers();

        return apiResponse($this->containerVpn, ['pid_numbers' => $pidNumbers]);
    }

    public function start(Container $container)
    {
        $this->containerVpn->setModel($container);
        $this->containerVpn->start();

        return apiResponse($this->containerVpn);
    }

    public function reload(Container $container)
    {
        $this->containerVpn->setModel($container);
        $this->containerVpn->reload();

        return apiResponse($this->containerVpn);
    }

    public function restart(Container $container)
    {
        $this->containerVpn->setModel($container);
        $this->containerVpn->restart();

        return apiResponse($this->containerVpn);
    }

    public function stop(Container $container)
    {
        $this->containerVpn->setModel($container);
        $this->containerVpn->stop();

        return apiResponse($this->containerVpn);
    }

    public function toggleStartOnBoot(Container $container, Request $request)
    {
        $this->containerVpn->setModel($container);

        $status = $request->input('status');
        $this->containerVpn->toggleStartOnBoot($status);

        return apiResponse($this->containerVpn);
    }

    public function downloadConfig(Container $container)
    {
        $this->containerVpn->setModel($container);

        $username = $request->input('username');
        $download = $this->containerVpn->downloadConfig($username);

        $destination = $download['destination'];
        $filename = $download['filename'];
        $headers = ['Content-Type' => 'application/octet-stream'];
        return response()->download($destination, $filename, $headers);
    }
}