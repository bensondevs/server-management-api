<?php

namespace App\Jobs\Container\Samba\Share\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, SambaUser, SambaShare, SambaShareUser };

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class RemoveSambaShareUser implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $shareUser;

    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaShareUser $shareUser)
    {
        $this->shareUser = $shareUser;

        $this->amqpRepo = new AmqpRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shareUser = $this->shareUser;
        $share = $shareUser->share;
        $user = $shareUser->user;
        $container = $shareUser->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'remove samba share user',
            'container_id' => $container->id,
            'share_name' => $share->share_name,
            'username' => $user->username,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            SambaShareUser::where('samba_user_id', $sambaUser->id)
                ->where('samba_share_id', $sambaShare->id)
                ->first()
                ->delete();
        }
    }
}
