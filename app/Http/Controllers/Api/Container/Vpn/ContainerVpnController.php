<?php

namespace App\Http\Controllers\Api\Container;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Vpn\{
    CreateVpnUserApiRequest as CreateUserRequest,
    RevokeVpnUserApiRequest as RevokeUserRequest,
    ChangeVpnUserSubnetIpRequest as ChangeUserSubnetIpRequest
};
use App\Models\Container;
use App\Repositories\ContainerVpnRepository;

class ContainerVpnController extends Controller
{
    /**
     * Container VPN Repository Class Container
     * 
     * @var \App\Repositories\ContainerVpnRepository
     */
    private $vpn;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\ContainerVpnRepository  $vpn
     * @return void
     */
    public function __construct(ContainerVpnRepository $vpn)
    {
        $this->vpn = $vpn;
    }

    /**
     * Get current information about Container VPN Service
     * 
     * @param \App\Models\Container  $container
     * @return \Illuminate\Support\Facades\Response
     */
    public function containerVpn(Container $container)
    {
        $this->vpn->setModel($container);
        $informations = $this->vpn->completeCheck();
        return response()->json(['vpn_informations' => $informations]);
    }

    /**
     * Send command to start Container VPN Service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function start(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnStatus = $this->vpn->start();
        return apiResponse($this->vpn, ['vpn_status' => $vpnStatus]);
    }

    /**
     * Send command to reload Container VPN Service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function reload(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnStatus = $this->vpn->reload();
        return apiResponse($this->vpn, ['vpn_status' => $vpnStatus]);
    }

    /**
     * Send command to restart Container VPN Service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function restart(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnStatus = $this->vpn->restart();
        return apiResponse($this->vpn, ['vpn_status' => $vpnStatus]);
    }

    /**
     * Send command to stop Container VPN Service
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function stop(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnStatus = $this->vpn->stop();
        return apiResponse($this->vpn, ['vpn_status' => $vpnStatus]);
    }

    /**
     * Send command to enable Container VPN Service on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function enable(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnEnability = $this->vpn->enable();
        return apiResponse($this->vpn, ['vpn_enability' => $vpnEnability]);
    }

    /**
     * Send command to disable Container VPN Service on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function disable(Container $container)
    {
        $this->vpn->setModel($container);
        $vpnEnability = $this->vpn->disable();
        return apiResponse($this->vpn, ['vpn_enability' => $vpnEnability]);
    }

    /**
     * Send command to enable or disable Container VPN Service on boot
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function toggleEnability(Container $container)
    {
        $this->vpn->setModel($container);
        $enability = $this->vpn->toggleEnability();
        return apiResponse($this->vpn, ['vpn_enability' => $enability]);
    }

    /**
     * Send command to change Container VPN Subnet
     * 
     * @param Illuminate\Http\Request  $request
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function changeSubnet(Request $request, Container $container)
    {
        $this->vpn->setModel($container);

        $subnet = $request->input('subnet');
        $this->vpn->changeSubnet($subnet);

        return apiResponse($this->vpn);
    }

    /**
     * Populate VPN attached Subnet IPs
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function subnetIps(Container $container)
    {
        $this->vpn->setModel($container);
        $subnetIps = $this->vpn->subnetIps();

        return response()->json(['subnet_ips' => $subnetIps]);
    }

    /**
     * Populate VPN Settings Attributes and Values
     * 
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function settings(Container $container)
    {
        //
    }
}
