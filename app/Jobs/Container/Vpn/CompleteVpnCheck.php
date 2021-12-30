<?php

namespace App\Jobs\Container\Vpn;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CompleteVpnCheck extends ContainerBaseJob implements ShouldQueue
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
     * @param  \App\Models\Container  $serverContainer
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
            'command' => 'complete vpn check',
            'container_id' => $container->id,
        ]);

        // Handle update status
        $container->vpn_status = $response['vpn_status'];

        // Handle update PID Numbers
        $vpnPidNumbers = $response['vpn_pid_numbers'];
        if (is_string($vpnPidNumbers)) {
            $vpnPidNumbers = explode(' ', $vpnPidNumbers);
        }
        $container->vpn_pid_numbers = $vpnPidNumbers;

        // Handle update Start On Boot Status
        $container->vpn_enability = $response['vpn_enability'];

        $container->save();
    }
}
