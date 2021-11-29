<?php

namespace App\Jobs\Container\Samba\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Models\SambaUser;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class CreateSambaUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $serverContainer;
    private $userData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, array $userData)
    {
        $this->serverContainer = $serverContainer;
        $this->userData = $userData;

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
        $username = $this->userData['username'];
        $password = $this->userData['password'];
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $uuid = $this->amqpRepo->publishJson([
            'command' => 'create samba user',
            'container_id' => $container->id,
            'username' => $username,
            'password' => $password,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server, $uuid);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $sambaUser = new SambaUser(['username' => $username]);
            $sambaUser->container_id = $container->id;
            $sambaUser->unencrypted_password = $password;
            $sambaUser->save();
        }

    }
}
