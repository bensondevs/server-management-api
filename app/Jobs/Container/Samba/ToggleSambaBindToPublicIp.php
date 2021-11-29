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

use App\Repositories\AmqpRepository;

class ToggleSambaBindToPublicIp implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $serverContainer;
    private $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, string $status)
    {
        $this->serverContainer = $serverContainer;
        $this->status = $status;

        $this->amqpRepo = new AmqpRepository();
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
            'command' => 'toggle bind samba to public ip',
            'container_id' => $container->id,
            'status' => $status,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);
    }
}
