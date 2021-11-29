<?php

namespace App\Jobs\Container\Vpn;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ VpnUser, Container };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class DownloadVpnConfig extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Targat VPN User model to be downloaded the config
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
        $username = $vpnUser->username;
        $container = $vpnUser->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'download vpn config',
            'container_id' => $container->id,
            'username' => $username,
        ]);

        $this->recordResponse($response, ['config']);

        if ($response['status'] == 'success') {
            $vpnUser->encoded_config_content = $response['config'];
            $vpnUser->save();
        }

    }
}
