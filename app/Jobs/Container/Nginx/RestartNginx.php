<?php

namespace App\Jobs\Container\Nginx;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

use App\Models\{ Container, NfsLocation };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class RestartNginx extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout seconds until job execution is failed
     * 
     * @var int
     */
    public $timeout = 1200;

    /**
     * Target NGINX Location model container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\ServerContainer  $serverContainer
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
            'command' => 'restart nginx',
            'container_id' => $container->id,
        ]);

        $this->recordResponse($response, ['nginx_status']);

        if ($response['status'] == 'success') {
            $container->nginx_status = $response['nginx_status'];
            $container->save();
        }
    }
}
