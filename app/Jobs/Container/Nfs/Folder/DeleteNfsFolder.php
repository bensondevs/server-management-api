<?php

namespace App\Jobs\Container\Nfs\Folder;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\NfsFolder;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class DeleteNfsFolder extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target NFS Folder container
     * 
     * @var \App\Models\NfsFolder
     */
    private $nfsFolder;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\NfsFolder  $nfsFolder
     * @return void
     */
    public function __construct(NfsFolder $nfsFolder)
    {
        parent::__construct();

        $this->nfsFolder = $nfsFolder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nfsFolder = $this->nfsFolder;
        $container = $nfsFolder->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'delete nfs folder',
            'container_id' => $container->id,
            'folder_name' => $nfsFolder->folder_name,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $nfsFolder->delete();
        }
    }
}
