<?php

namespace App\Jobs\Container\Nfs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Container\ContainerBaseJob;

use App\Models\NfsExport;
use App\Traits\TrackExecution;

class DeleteNfsExport extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target NFS Export
     * 
     * @var \App\Models\NfsExport  $nfsExport
     */
    private $nfsExport;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\NfsExport  $nfsExport
     * @return void
     */
    public function __construct(NfsExport $nfsExport)
    {
        parent::__construct();
        $this->nfsExport = $nfsExport;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $export = $this->nfsExport;
        $container = $export->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'uuid' => $requestId,
            'command' => 'delete nfs export',
            'container_id' => $export->container_id,
            'target_folder' => $export->target_folder,
            'ip_address' => $export->ip_address,
        ]);
        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $export->delete();
        }
    }
}
