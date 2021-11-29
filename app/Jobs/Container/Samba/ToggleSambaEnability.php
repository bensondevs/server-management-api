<?php

namespace App\Jobs\Container\Samba;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class ToggleSambaEnability extends ContainerBaseJob implements ShouldQueue
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
     * Target status container
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

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'start on boot toogle samba',
            'container_id' => $container->id,
            'status' => $status,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);
    }
}