<?php

namespace App\Jobs\Container\Samba\Share\Group;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ SambaGroup, SambaShare, SambaShareGroup };

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class AddSambaShareGroup implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $share;
    private $group;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaShare $share, SambaGroup $group)
    {
        $this->share = $share;
        $this->group = $group; 

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
        $group = $this->group;
        $container = $share->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'add samba share group',
            'container_id' => $container->id,
            'group_name' => $group->group_name,
            'share_name' => $share->share_name,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $shareGroup = SambaShareGroup::create([
                'container_id' => $container->id,
                'samba_group_id' => $group->id,
                'samba_share_id' => $share->id,
            ]);
        }

    }
}
