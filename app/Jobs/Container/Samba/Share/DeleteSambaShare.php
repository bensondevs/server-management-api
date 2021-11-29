<?php

namespace App\Jobs\Container\Samba\Share;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\SambaShare;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class DeleteSambaShare extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Target samba share model container
     * 
     * @var \App\Models\SambaShare
     */
    private $share;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\SambaShare  $share
     * @return void
     */
    public function __construct(SambaShare $share)
    {
        parent::__construct();
        $this->share = $share;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $share = $this->share;
        $container = $share->container;
        $server = $container->server;

        $response = $this->sendRequest($server, [
            'command' => 'delete samba share',
            'container_id' => $container->id,
            'share_name' => $share->share_name,
        ]);
        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $share->delete();
        }
    }
}
