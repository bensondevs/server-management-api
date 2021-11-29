<?php

namespace App\Jobs\Container\Nginx;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\NfsLocation;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class RemoveNginxLocation implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target NGINX Location model container
     * 
     * @var \App\Models\NfsLocation
     */
    private $nginxLocation;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\NginxLocation  $nginxLocation
     * @return void
     */
    public function __construct(NginxLocation $nginxLocation)
    {
        parent::__construct();
        $this->nginxLocation = $nginxLocation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nginxLocation = $this->nginxLocation;
        $container = $nginxLocation->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'remove nginx location',
            'container_id' => $container->id,
            'location' => $nginxLocation->location,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $nginxLocation->delete();
        }
    }
}