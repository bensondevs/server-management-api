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
use App\Jobs\Container\ContainerBaseJob;

class AddSambaShareUser extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout in seconds until the job execution terminated
     * 
     * @var int
     */
    public $timeout = 1200;

    /**
     * Target share model container
     * 
     * @var \App\Models\SambaShare
     */
    private $share;

    /**
     * Added user model container
     * 
     * @var \App\Models\SambaUser
     */
    private $user;

    /**
     * Create a new job instance and set the target share and added user.
     * 
     * First parameter will set the target samba share and
     * Second parameter will set the added user to the share.
     *
     * @param  \App\Models\SambaShare $share
     * @param  \App\Models\SambaUser  $user
     * @return void
     */
    public function __construct(SambaShare $share, SambaUser $user)
    {
        parent::__construct();

        $this->share = $share;
        $this->user = $user;
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

        $response = $this->sendRequest($server, [
            'command' => 'add samba share user',
            'container_id' => $container->id,
            'username' => $username,
            'share_name' => $shareName,
        ]);

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