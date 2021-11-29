<?php

namespace App\Jobs\Container\Vpn;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\VpnUser;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class ChangeVpnUserSubnetIp extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target VPN User container
     * 
     * @var \App\Models\VpnUser
     */
    private $vpnUser;
    
    /**
     * Subnet IP container
     * 
     * @var string
     */
    private $subnetIp;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\VpnUser  $vpnUser
     * @param string  $subnetIp
     * @return void
     */
    public function __construct(VpnUser $vpnUser, string $subnetIp)
    {
        parent::__construct();
        $this->vpnUser = $vpnUser;
        $this->subnetIp = $subnetIp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $vpnUser = $this->vpnUser;
        $container = $vpnUser->container;
        $server = $container->server;

        $subnetIp = $this->subnetIp;
        $assignedSubnetIp = VpnUser::findFreeSubnetIp($container->id, $subnetIp);

        $this->sendRequest($server, [
            'command' => 'change vpn user subnet ip',
            'contaner_id' => $container->id,
            'username' => $vpnUser->username,
            'assigned_subnet_ip' => $assignedSubnetIp,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $vpnUser->vpn_subnet = $subnetIp;
            $vpnUser->assigned_subnet_ip = $assignedSubnetIp;
            $vpnUser->save();
        }
    }
}
