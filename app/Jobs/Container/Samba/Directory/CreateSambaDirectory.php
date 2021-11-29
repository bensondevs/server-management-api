<?php

namespace App\Jobs\Container\Samba\Directory;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, SambaDirectory };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateSambaDirectory extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target container model to be checked container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * Created directory name
     * 
     * @var string
     */
    private $directoryName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, string $directoryName)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
        $this->directoryName = $directoryName;
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
        $directoryName = $this->directoryName;

        $response = $this->sendRequest($server, [
            'command' => 'create samba directory',
            'container_id' => $container->id,
            'directory_name' => $directoryName,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            SambaDirectory::create([
                'container_id' => $container->id,
                'directory_name' => $directoryName,
            ]);
        }
    }
}
