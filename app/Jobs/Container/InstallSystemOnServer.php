<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Models\Server;

use App\Enums\Container\ContainerStatus;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class InstallSystemOnServer implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

    private $serverContainer;
    private $server;

    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        $this->serverContainer = $serverContainer;

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

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'install system',
            'container_id' => $container->id,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response, ['installation', 'message'], null, function () {
            // Send email to administrator

            echo 'Send email to administrator...';
        });

        $container->status = ContainerStatus::Running;
        $container->system_installed_at = now();
        $container->save();
    }
}
