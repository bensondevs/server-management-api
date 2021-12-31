<?php

namespace App\Jobs\Container\Samba;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CompleteSambaCheck extends ContainerBaseJob implements ShouldQueue
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
            'command' => 'complete samba check',
            'container_id' => $container->id,
        ]);

        $this->recordResponse($response, [
            'samba_status',
            'samba_pid_numbers',
            'samba_enability',
        ]);

        if (isset($response['samba_status'])) {
            $status = $response['samba_status'];

            if (isset($status['smbd'])) {
                $container->samba_smbd_status = $status['smbd'];
            }

            if (isset($status['nmbd'])) {
                $container->samba_nmbd_status = $status['nmbd'];
            }
        }

        if (isset($response['samba_pid_numbers'])) {
            $container->samba_pid_numbers = $response['samba_pid_numbers'];
        }

        if (isset($response['samba_enability'])) {
            $enablility = $response['samba_enability'];

            if (isset($enability['smbd'])) {
                $container->samba_smbd_start_on_boot_status = $enability['smbd'];
            }

            if (isset($enability['nmbd'])) {
                $container->samba_nmbd_start_on_boot_status = $enability['nmbd'];
            }

        }

        $container->save();
    }
}
