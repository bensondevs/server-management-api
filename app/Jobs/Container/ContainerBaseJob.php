<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\TrackExecution;
use App\Repositories\AmqpRepository;

use App\Models\{ Container, Server };

class ContainerBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job execution timeout limit
     * 
     * @var int
     */
    public $timeout = 1200;

    /**
     * AMQP Repository class container
     * 
     * @var \App\Repositories\AmqpRepository
     */
    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @return void
     */
    public function __construct()
    {
        $this->amqpRepo =  new AmqpRepository();
    }

    /**
     * Send the AMQP message request
     * 
     * @param \App\Models\Server  $server
     * @param array  $input
     * @return array  $response
     */
    public function sendRequest(Server $server, array $input)
    {
        if (! isset($input['uuid'])) {
            $input['uuid'] = generateUuid();
        }

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson($input);
        $response = $this->amqpRepo->consumeServerResponse($server, $input['uuid']);

        return $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
