<?php

namespace App\Jobs\Container\Vpn;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class ToggleVpnEnability extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    /**
     * Targat container model to be checked container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * New VPN status container
     * 
     * @var string
     */
    private $status;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Container  $container
     * @param string  $status
     * @return void
     */
    public function __construct(Container $serverContainer, string $status)
    {
        $this->serverContainer = $serverContainer;
        $this->status = $status;
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
        $status = $this->status;

        $response = $this->sendRequest($server, [
            'command' => 'toggle vpn enability',
            'container_id' => $container->id,
            'status' => $status,
        ]);

        $this->recordResponse($response, ['vpn_enability']);

        if ($response['status'] == 'success') {
            $container->vpn_enability = $response['vpn_enability'];
            $container->save();
        }
    }
}
