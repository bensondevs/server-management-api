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

class RevokeVpnUser extends ContainerBaseJob implements ShouldQueue
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
     * Create a new job instance.
     *
     * @param \App\Models\VpnUser  $vpnUser
     * @return void
     */
    public function __construct(VpnUser $vpnUser)
    {
        parent::__construct();
        $this->vpnUser = $vpnUser;
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

        $response = $this->sendRequest($server, [
            'command' => 'revoke vpn user',
            'container_id' => $container->id,
            'username' => $vpnUser->username,
        ]);
        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $vpnUser->delete();
        }
    }
}
