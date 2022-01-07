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
use App\Jobs\Container\ContainerBaseJob;

class AddSambaShareGroup extends ContainerBaseJob implements ShouldQueue
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
     * Target share model container
     * 
     * @var \App\Models\SambaShare|null
     */
    private $share;

    /**
     * Added group model container
     * 
     * @var \App\Models\SambaGroup|null
     */
    private $group;

    /**
     * Create a new job instance.
     *
     * @var \App\Models\SambaShare  $share
     * @var \App\Models\SambaGroup  $group
     * @return void
     */
    public function __construct(SambaShare $share, SambaGroup $group)
    {
        parent::__construct();

        $this->share = $share;
        $this->group = $group; 
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

        $response = $this->sendRequest($server, [
            'command' => 'add samba share group',
            'container_id' => $container->id,
            'group_name' => $group->group_name,
            'share_name' => $share->share_name,
        ]);

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
