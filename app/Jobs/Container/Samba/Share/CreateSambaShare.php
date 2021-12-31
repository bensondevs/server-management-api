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
use App\Repositories\AmqpRepository;

class CreateSambaShare implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1200;

    private $serverContainer;
    private $directoryName;

    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, string $directoryName)
    {
        $this->serverContainer = $serverContainer;
        $this->directoryName = $directoryName;

        $this->amqpRepo = new AmqpRepository();
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

        $directory = SambaDirectory::findOrCreate($container, $directoryName);

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'command' => 'create samba share',
            'container_id' => $container->id,
            'directory_name' => $directory->directory_name,
            'share_name' => $directory->directory_name,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server);

        $this->recordResponse($response);

        if ($response['status'] == 'success') {
            $config = $response['share_config_content'];
            $share = new SambaShare([
                'container_id' => $container->id,
                'share_name' => $directory->directory_name,
                'share_content_config' => $config,
            ]);
            $share->save();
        }

    }
}
