<?php

namespace App\Jobs\Container\Samba\Group;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Models\SambaGroup;

use App\Traits\TrackExecution;

use App\Repositories\AmqpRepository;

class DeleteSambaGroup implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $amqpRepo;
    private $group;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaGroup $group)
    {
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
        $group = $this->group;
        $container = $group->container;
        $server = $container->server;

        $this->amqpRepo->connectServerQueue($server);
        $uuid = $this->amqpRepo->publishJson([
            'command' => 'delete samba group',
            'container_id' => $container->id,
            'group_name' => $groupName,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server, $uuid);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $group->delete();
        }
    }
}
