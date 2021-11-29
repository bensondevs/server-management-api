<?php

namespace App\Jobs\Container\Nfs\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

use App\Models\{ Container, NfsFolder, NfsExport };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateNfsExport extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Container target model container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * Export data to fill Nfs Export
     * 
     * @var array
     */
    private $exportData;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Container  $serverContainer
     * @param array  $exportData
     * @return void
     */
    public function __construct(Container $serverContainer, array $exportData)
    {
        parent::__construct($serverContainer);

        $this->exportData = $exportData;
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

        $export = $this->exportData;
        $nfsFolder = NfsFolder::findOrFail($export['nfs_folder_id']);
        $ipAddress = $export['ip_address'];
        $permissions = $export['permissions'];

        $response = $this->sendRequest($server, [
            'command' => 'create nfs export',
            'container_id' => $container->id,
            'target_folder' => $nfsFolder->folder_name,
            'ip_address' => $ipAddress,
            'permissions' => $permissions,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $export = new NfsExport([
                'container_id' => $container->id,
                'nfs_folder_id' => $nfsFolder->id,
                'permissions' => $permissions,
            ]);
            $export->ip_address = $ipAddress;
            $export->save();
        }
    }
}