<?php

namespace App\Jobs\Container\Nginx\Location;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\NfsLocation;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class DeleteNginxLocation extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target NFS Location model container
     * 
     * @var \App\Models\NfsLocation
     */
    private $nfsLocation;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\NfsLocation  $nfsLocation
     * @return void
     */
    public function __construct(NfsLocation $nfsLocation)
    {
        parent::__construct();
        $this->nfsLocation = $nfsLocation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $location = $this->nfsLocation;
        $container = $location->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'complete nginx check',
            'container_id' => $container->id,
        ]);

        if ($response['status'] == 'success') {
            $location->delete();
        }
    }
}
