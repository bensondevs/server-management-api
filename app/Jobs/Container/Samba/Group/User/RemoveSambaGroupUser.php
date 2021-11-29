<?php

namespace App\Jobs\Container\Samba\Group\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{
    Container,
    SambaUser,
    SambaGroup,
    SambaGroupUser
};

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class RemoveSambaGroupUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;

    private $groupUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaGroupUser $groupUser)
    {
        $this->groupUser = $groupUser;

        $this->amqpRepo = new AmqpRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $groupUser = $this->groupUser;
        $container = $groupUser->container;
        $server = $container->server;
        
        $user = $groupUser->user;
        $group = $groupUser->group;
        
        $requestId = generateUuid();

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'uuid' => $requestId,
            'command' => 'remove samba group user',
            'container_id' => $container->id,
            'username' => $user->username,
            'group_name' => $group->group_name,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server, $requestId);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $groupUser->delete();
        }
    }
}
