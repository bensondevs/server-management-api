<?php

namespace App\Jobs\Container\Samba\Group\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ SambaUser, SambaGroup, SambaGroupUser };

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class AddSambaGroupUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;

    private $sambaUser;
    private $sambaGroup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaGroup $sambaGroup, SambaUser $sambaUser)
    {
        $this->sambaGroup = $sambaGroup;
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
        $user = $this->sambaUser;
        $group = $this->sambaGroup;
        $container = $group->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'add samba group user',
            'container_id' => $container->id,
            'username' => $user->username,
            'group_name' => $group->group_name,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            SambaGroupUser::create([
                'container_id' => $container->id,
                'samba_group_id' => $group->id,
                'samba_user_id' => $user->id,
            ]);
        }
    }
}
