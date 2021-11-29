<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Container;

use App\Jobs\Container\RecreateContainer as RecreateContainerJob;

class RecreateContainer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'container:recreate {container_id : Target containe ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $containerId = $this->argument('container_id');
        $container = Container::findOrFail($containerId);
        
        $job = new RecreateContainerJob($container);
        $container->trackDispatch($job);

        return $this->info('Successfully recreating new container.');
    }
}
