<?php

namespace App\Jobs\Container\Nginx;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;
use App\Traits\TrackExecution;
use App\Jobs\Container\ContainerBaseJob;

class CompleteNginxCheck extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Targat container model to be checked container
     * 
     * @var \App\Models\Container
     */
    private $serverContainer;

    /**
     * Create a new job instance.
     *
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
            'command' => 'complete nginx check',
            'container_id' => $container->id,
        ]);
        $this->recordResponse($response, [
            'nginx_status',
            'nginx_pid_numbers',
            'nginx_enability'
        ]);

        // Handle NGINX status
        $container->nginx_status = $response['nginx_status'];

        // Handle NGINX PID Numbers
        $container->nginx_pid_numbers = $response['nginx_pid_numbers'];

        // Handle NGINX Enability
        $container->nginx_enability = $response['nginx_enability'];
        $container->save();
    }
}
