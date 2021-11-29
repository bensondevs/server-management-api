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

class ChangeVpnSubnet extends ContainerBaseJob implements ShouldQueue
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
     * Target Subnet container
     * 
     * @var string
     */
    private $subnet;

    /**
     * Optional specified netmask
     * 
     * @var string|mixed
     */
    private $netmask;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @param string  $subnet
     * @param string|mixed  $netmask
     * @return void
     */
    public function __construct(Container $serverContainer, string $subnet, $netmask = null)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
        $this->subnet = $subnet;
        $this->netmask = $netmask;
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
        $subnet = $this->subnet;
        $netmask = $this->netmask ?: null;

        $response = $this->sendRequest($server, [
            'command' => 'change vpn subnet',
            'contaner_id' => $container->id,
            'subnet' => $subnet,
            'netmask' => $netmask,
        ]);
        $this->recordResponse($response);
    }
}
