<?php

namespace App\Jobs\Container\Nfs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, NfsExport };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class UpdateNfsExport extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Container target to create NFS Export
     * 
     * @var \App\Models\Container
     */
    private $nfsExport;

    /**
     * Export data to fill Nfs Export
     * 
     * @var array
     */
    private $exportData;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\NfsExport  $nfsExport
     * @param array  $exportData
     * @return void
     */
    public function __construct(NfsExport $nfsExport, array $exportData)
    {
        parent::__construct();

        $this->nfsExport = $nfsExport;
        $this->exportData = $exportData;
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
            'command' => 'update nfs export',
            'container_id' => $container->id,

            'target_folder' => $export->target_folder,
            'ip_address' => $export->ip_address,
            'permissions' => $this->exportData['permissions'],
        ]);
        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $export->permissions = $permissions;
            $export->save();
        }
    }
}
