<?php

namespace App\Jobs\Container\Nfs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Traits\TrackExecution;
use App\Models\Container;
use App\Jobs\Container\ContainerBaseJob;

class ToggleNfsStartOnBoot extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Container model target container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * NFS Start on Boot Status container
     * 
     * @var string
     */
    private $status;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @param string  $status
     * @return void
     */
    public function __construct(Container $serverContainer, string $status)
    {
        parent::__construct();

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
            'command' => 'start on boot toggle nfs',
            'container_id' => $container->id,
            'status' => $status,
        ]);
        $this->recordResponse($response, ['nfs_start_on_boot_status']);

        $container->nfs_start_on_boot_status = $response['nfs_start_on_boot_status'];
        $container->save();
    }
}
