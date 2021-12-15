<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CheckContainerExistsInServer extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job execution timeout
     * 
     * @var int
     */
    public $timeout = 3600;

    /**
     * Target container container
     * 
     * @var \App\Models\Container|null
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
            'command' => 'container exists',
            'container_id' => $container->id,
        ]);
        $this->recordResponse($response);
    }
}
