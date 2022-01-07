<?php

namespace App\Jobs\Container\Samba\Share\Group;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SambaShareGroup;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class RemoveSambaShareGroup extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout seconds until the job execution terminated
     * 
     * @var int
     */
    public $timeout = 1200;

    /**
     * Target pivot share and group model container
     * 
     * @var \App\Models\SambaShareGroup  $shareGroup
     */
    private $shareGroup;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\SambaShareGroup  $shareGroup
     * @return void
     */
    public function __construct(SambaShareGroup $shareGroup)
    {
        parent::__construct();
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

        $response = $this->sendRequest($server, [
            'command' => 'remove samba share group',
            'container_id' => $container->id,
            'share_name' => $share->share_name,
            'group_name' => $group->group_name,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $shareGroup->delete();
        }
    }
}
