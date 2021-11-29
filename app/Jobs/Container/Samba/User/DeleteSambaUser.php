<?php

namespace App\Jobs\Container\Samba\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SambaUser;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class DeleteSambaUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    
    private $sambaUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaUser $sambaUser)
    {
        $this->sambaUser = $sambaUser;

        $this->amqpRepo = new AmqpRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sambaUser = $this->sambaUser;
        $container = $sambaUser->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'delete samba user',
            'container_id' => $container->id,
            'username' => $sambaUser->username,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $sambaUser->delete();
        }

    }
}
