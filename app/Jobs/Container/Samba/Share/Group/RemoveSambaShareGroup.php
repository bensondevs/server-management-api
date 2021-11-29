<?php

namespace App\Jobs\Container\Samba\Share\Group;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class RemoveSambaShareGroup implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $shareGroup;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaShareGroup $shareGroup)
    {
        $this->amqpRepo = new AmqpRepository;
        $this->shareGroup = $shareGroup;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shareGroup = $this->shareGroup;
        $container = $shareGroup->container;
        $server = $container->server;
        
        $share = $shareGroup->share;
        $group = $shareGroup->group;

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'remove samba share group',
            'container_id' => $container->id,
            'share_name' => $share->share_name,
            'group_name' => $group->group_name,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $shareGroup->delete();
        }
    }
}
