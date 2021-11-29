<?php

namespace App\Jobs\Container\Nfs\Folder;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, NfsFolder };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateNfsFolder extends ContainerBaseJob implements ShouldQueue
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
     * Folder name to be created container
     * 
     * @var string
     */
    private $folderName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, string $folderName)
    {
        parent::__construct();

        $this->serverContainer = $serverContainer;
        $this->folderName = $folderName;
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
        $folderName = $this->folderName;

        $response = $this->sendRequest($server, [
            'command' => 'create nfs folder',
            'container_id' => $container->id,
            'folder_name' => $folderName,
        ]);
        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $nfsFolder = NfsFolder::create([
                'container_id' => $container->id,
                'folder_name' => $folderName,
            ]);
        }

    }
}