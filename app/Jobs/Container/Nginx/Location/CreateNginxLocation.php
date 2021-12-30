<?php

namespace App\Jobs\Container\Nginx\Location;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, NginxLocation };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateNginxLocation extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout seconds of job exection
     * 
     * @var int
     */
    public $timeout = 1200;

    /**
     * Target container model container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Location name container
     * 
     * @var string|null
     */
    private $location;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Container  $serverContainer
     * @param  string  $location
     * @return void
     */
    public function __construct(Container $serverContainer, string $location)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
        $this->location = $location;
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
        $location = $this->location;

        $response = $this->sendRequest($server, [
            'command' => 'create nginx location',
            'container_id' => $container->id,
            'location' => $location,
        ]);

        $this->recordResponse($response, ['location', 'config']);

        if (! $nginxLocation = NginxLocation::findInContainer($container, $location)) {
            $nginxLocation = new NginxLocation;
        }
        $nginxLocation->location = isset($response['location']) ?
            $response['location'] : 
            $location;
        $nginxLocation->config = isset($response['config']) ?
            $response['config'] : 
            json_encode([]);
        $nginxLocation->save();
    }
}
