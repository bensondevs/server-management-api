<?php

namespace App\Jobs\Container\Nfs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class BindNfsPublicIp  extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target container model to be executed
     * 
     * @var \App\Models\Container|null
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
            'command' => 'bind nfs public ip',
            'container_id' => $container->id,
        ]);

        $this->recordResponse($response, [
            'bind_public_ip',
        ]);

        $container->bind_public_ip = $response['bind_public_ip'];
        $container->save();
    }
}
