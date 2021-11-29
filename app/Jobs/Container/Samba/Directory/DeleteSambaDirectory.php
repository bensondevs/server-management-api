<?php

namespace App\Jobs\Container\Samba\Directory;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ SambaDirectory };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class DeleteSambaDirectory extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target directory
     * 
     * @var \App\Models\SambaDirectory
     */
    private $directory;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SambaDirectory $directory)
    {
        parent::__construct();
        $this->directory = $directory;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $directory = $this->directory;
        $container = $directory->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'delete samba directory',
            'container_id' => $container->id,
            'directory_name' => $directory->directory_name,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $directory->delete();
        }
    }
}
