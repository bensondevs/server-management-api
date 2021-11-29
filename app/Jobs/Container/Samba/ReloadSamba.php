<?php

namespace App\Jobs\Container\Samba;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

use App\Models\Container;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class ReloadSamba implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $serverContainer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        $this->serverContainer = $serverContainer;

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

        $requestId = generateUuid();

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'uuid' => $requestId,
            'command' => 'reload samba',
            'container_id' => $container->id,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server, $requestId);

        $this->recordResponse($response, ['samba_status']);

        if (isset($response['samba_status'])) {
            $status = $response['samba_status'];
            $container->samba_smbd_status = isset($status['smbd']) ?
                $status['smbd'] : 0;
            $container->samba_nmbd_status = isset($status['nmbd']) ?
                $status['nmbd'] : 0;
            $container->save();
        }
    }
}
