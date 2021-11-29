<?php

namespace App\Jobs\Container\Nginx;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

use App\Models\Container;
use App\Models\NginxLocation;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class CreateNginxLocation implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;

    private $serverContainer;
    private $location;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, string $location)
    {
        $this->serverContainer = $serverContainer;
        $this->location = $location;

        $this->amqpRepo = new AmqpRepository;
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

        $requestId = generateUuid();

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'uuid' => $requestId,
            'command' => 'create nginx location',
            'container_id' => $container->id,
            'location' => $location,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response, ['location', 'config']);

        $location = $response['location'];
        $config = $response['config'];

        if (! $nginxLocation = NginxLocation::findInContainer($container, $location)) {
            $nginxLocation = new NginxLocation;
        }
        $nginxLocation->location = $location;
        $nginxLocation->config = $config;
        $nginxLocation->save();
    }
}
