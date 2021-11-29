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

class DisableVpn extends ContainerBaseJob implements ShouldQueue
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
     * Create a new job instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
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

        $response = $this->sendRequest($server, [
            'command' => 'disable vpn',
            'container_id' => $container->id,
        ]);

        $this->recordResponse($server, ['vpn_enability']);

        if ($response['status'] == 'success') {
            $container->vpn_enability = $response['vpn_enability'];
            $container->save();
        }
    }
}
