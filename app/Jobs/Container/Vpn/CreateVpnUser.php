<?php

namespace App\Jobs\Container\Vpn;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, VpnUser };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateVpnUser extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Targat container model to be checked container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * VPN User data container
     * 
     * @var array
     */
    private $vpnUserData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, array $vpnUserData)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
        $this->vpnUserData = $vpnUserData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $container = $this->serverContainer;
        $server = $container->server;

        $data = $this->vpnUserData;
        $username = $data['username'];
        $subnet = $data['subnet_ip'];
        $assignedSubnetIp = VpnUser::findFreeSubnetIp($container->id, $subnet);

        $response = $this->sendRequest($server, [
            'command' => 'create vpn user',
            'container_id' => $container->id,
            'username' => $username,
            'assigned_subnet_ip' => $assignedSubnetIp,
        ]);

        $this->recordResponse($response, ['config_content']);

        if ($response['status'] == 'success') {
            try {
                $vpnUser = new VpnUser;
                $vpnUser->container_id = $container->id;
                $vpnUser->username = $username;
                $vpnUser->encoded_config_content = isset($response['config_content']) ? 
                    $response['config_content'] : '[]';
                $vpnUser->vpn_subnet = $subnet;
                $vpnUser->assigned_subnet_ip = $assignedSubnetIp;
                $vpnUser->save();
            } catch (Exception $e) {
                $message = $e->getMessage();
                throw new Exception($message);
            }
        }
    }
}