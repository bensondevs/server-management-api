<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\AmqpRepository;
use App\Models\Container;
use App\Traits\TrackExecution;

class RecreateContainer implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Server container class container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * AMQP Repository class container
     * 
     * @var \App\Repositories\AmqpRepository|null
     */
    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Container  $serverContainer
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
        $subnetIp = $container->subnetIp;
        
        $this->amqpRepo->setModel($container);
        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'create container',
            'container_id' => $container->id,
            'plan_name' => 'free',
            'ip_address' => $subnetIp->ip_address,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);
    }
}
