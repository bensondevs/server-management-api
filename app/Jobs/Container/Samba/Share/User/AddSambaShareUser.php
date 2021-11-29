<?php

namespace App\Jobs\Container\Samba\Share\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ SambaUser, SambaShare, SambaShareUser };

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class AddSambaShareUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $share;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaShare $share, SambaUser $user)
    {
        $this->share = $share;
        $this->user = $user;

        $this->amqpRepo = new AmqpRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $share = $this->share;
        $user = $this->user;
        $container = $share->container;
        $server = $container->server;
        $username = $user->username;
        $shareName = $share->share_name;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'add samba share user',
            'container_id' => $container->id,
            'username' => $username,
            'share_name' => $shareName,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $sambaShareUser = SambaShareUser::create([
                'container_id' => $container->id,
                'samba_user_id' => $user->id,
                'samba_share_id' => $share->id,
            ]);
        }
    }
}