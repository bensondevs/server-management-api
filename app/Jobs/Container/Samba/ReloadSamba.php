<?php

namespace App\Jobs\Container\Samba;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;
use App\Enums\Container\Samba\{
    ContainerSambaNmbdStatus as NmbdStatus,
    ContainerSambaSmbdStatus as SmbdStatus
};

class ReloadSamba extends ContainerBaseJob implements ShouldQueue
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
     * Create a new job instance.
     *
     * @param  \App\Models\Container  $serverContainer
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
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

        $response = $this->sendRequest($server, [
            'command' => 'reload samba',
            'container_id' => $container->id,
        ]);

        $this->recordResponse($response, ['samba_status']);

        if (isset($response['samba_status'])) {
            $status = $response['samba_status'];
            $container->samba_smbd_status = isset($status['smbd']) ?
                $status['smbd'] : 
                SmbdStatus::Unknown;

            $container->samba_nmbd_status = isset($status['nmbd']) ?
                $status['nmbd'] : 
                NmbdStatus::Unknown;
            
            $container->save();
        }
    }
}
