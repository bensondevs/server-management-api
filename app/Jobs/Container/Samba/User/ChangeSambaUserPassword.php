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

class ChangeSambaUserPassword implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $sambaUser;
    private $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaUser $user, string $password)
    {
        $this->sambaUser = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sambaUser = $this->sambaUser;
        $password = $this->password;

        $container = $sambaUser->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'container_id' => $container->id,
            'command' => 'change samba user password',
            'username' => $sambaUser->username,
            'new_password' => $password,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        if ($response['status'] == 'success') {
            $sambaUser->unencrypted_password = $password;
            $sambaUser->save();
        }
    }
}
