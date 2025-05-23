<?php

namespace App\Jobs\Container\Samba\Share;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\{ Container, SambaDirectory, SambaShare };
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CreateSambaShare extends ContainerBaseJob implements ShouldQueue
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
     * Target container model container
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Target directory name
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
            'command' => 'create samba share',
            'container_id' => $container->id,
            'share_name' => $directoryName,
        ]);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $config = $response['share_config_content'];
            $share = new SambaShare([
                'container_id' => $container->id,
                'share_name' => $directoryName,
                'share_content_config' => $config,
            ]);
            $share->save();
        }

    }
}
