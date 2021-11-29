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

class ReloadNfs extends ContainerBaseJob implements ShouldQueue
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
     * Create a new job instance.
     *
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
            'command' => 'reload nfs',
            'container_id' => $container->id,
        ]);
        $this->recordResponse($response, ['nfs_status']);

        if ($response['status'] == 'success') {
            $container->nfs_status = $response['nfs_status'];
            $container->save();
        }
    }
}
