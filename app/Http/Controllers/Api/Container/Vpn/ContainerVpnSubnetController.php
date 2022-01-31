<?php

namespace App\Http\Controllers\Api\Container\Vpn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{ Container, VpnSubnet };
use App\Repositories\ContainerVpnRepository;

class ContainerVpnSubnetController extends Controller
{
    /**
     * Container VPN Repositort class container
     * 
     * @var \App\Repositories\ContainerVpnRepository
     */
    private $vpn;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\ContainerVpnRepository  $vpn
     * @return void
     */
    public function __construct(ContainerVpnRepository $vpn)
    {
        //
    }

    /**
     * Populate container existing vpn user subnets
     * 
     * @param  Illuminate\Support\Facades\Response
     */
    public function vpnSubnets(Container $container)
    {
        //
    }
}