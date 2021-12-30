<?php

namespace App\Http\Controllers\Api\Container\Vpn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Containers\Vpn\{
    CreateVpnUserApiRequest as CreateRequest,
    RevokeVpnUserApiRequest as RevokeRequest,
    ChangeVpnUserSubnetIpRequest as ChangeSubnetIpRequest
};
use App\Http\Resources\VpnUserResource;
use App\Models\{ Container, VpnUser };
use App\Repositories\ContainerVpnRepository;

class ContainerVpnUserController extends Controller
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
     * Populate Container Existing VPN Users
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function vpnUsers(Container $container)
    {
        $vpnUsers = $container->vpnUsers;
        $vpnUsers = VpnUserResource::collection($vpnUsers);
        return response()->json(['vpn_users' => $vpnUsers]);
    }

    /**
     * Create new Container VPN User
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Container  $container
     * @return Illuminate\Support\Facades\Response
     */
    public function create(CreateRequest $request, Container $container)
    {
        $input = $request->validated();

        $this->vpn->setModel($container);
        $this->vpn->createUser($input);

        return apiResponse($this->vpn);
    }

    /**
     * Show details about Container VPN User
     * 
     * @param \App\Models\Container $container
     * @param \App\Models\VpnUser  $vpnUser
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Container $container, VpnUser $vpnUser)
    {
        $vpnUser = new VpnUserResource($vpnUser);
        return response()->json(['vpn_user' => $vpnUser]);
    }

    /**
     * Download VPN User Configuration
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\VpnUser  $user
     */
    public function downloadConfig(Container $container, VpnUser $user)
    {
        $this->vpn->setModel($container);
        return response()->json([
            'config' => $this->vpn->userConfig($user),
        ]);
    }

    /**
     * Change VPN User Subnet IP
     * 
     * @param ChangeSubnetIpRequest  $request
     * @param \App\Models\Container  $container
     * @param \App\Models\VpnUser  $vpnUser
     * @return Illuminate\Support\Facades\Response
     */
    public function changeSubnetIp(
        ChangeSubnetIpRequest $request, 
        Container $container, 
        VpnUser $vpnUser
    ) {
        $subnetIp = $request->input('subnet_ip');
        $this->vpn->setModel($container);
        $this->vpn->changeUserSubnetIp($vpnUser, $subnetIp);
        return apiResponse($this->vpn);
    }

    /**
     * Revoke VPN User
     * 
     * @param \App\Models\Container  $container
     * @param \App\Models\VpnUser  $vpnUser
     */
    public function revoke(Container $container, VpnUser $vpnUser) 
    {
        $this->vpn->setModel($container);
        $this->vpn->revokeUser($vpnUser);
        return apiResponse($this->vpn);
    }
}
