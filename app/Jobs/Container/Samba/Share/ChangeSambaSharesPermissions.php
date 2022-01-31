<?php

namespace App\Jobs\Container\Samba\Share;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, SambaShare };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class ChangeSambaSharesPermissions extends ContainerBaseJob implements ShouldQueue
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
     * Target server container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Samba Share Ids array
     * 
     * @var array
     */
    private $sambaShareIds;

    /**
     * Samba Shares new permissions
     * 
     * @var array
     */
    private $permissions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Container $serverContainer, 
        array $sambaShareIds, 
        array $permissions
    ) {
        parent::__construct();
        $this->sambaShareIds = $sambaShareIds;
        $this->permissions = $permissions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $container = $this->serverContainer;
        $server = $container->server;
        $ids = $this->sambaShareIds;
        $permissions = $this->permissions;

        $response = $this->sendRequest($server, [
            'command' => 'change samba shares permissions',
            'container_id' => $container->id,
            'samba_share_ids' => $ids,
            'permissions' => $permissions,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            foreach (SambaShare::whereIn('id', $ids)->get() as $share) {
                $share->permissions_array = $permissions;
                $share->save();
            }
        }
    }
}
