<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;

use App\Traits\TrackExecution;

class ViewContainer implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $serverContainer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer)
    {
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

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'view container',
            'container_id' => $container->id,
        ]);
    }
}
