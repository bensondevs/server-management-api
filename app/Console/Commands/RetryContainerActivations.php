<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\Container\RetryContainerActivations as Job;

class RetryContainerActivations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'container:reactivate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry activation of container in server.';

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
        $retry = new Job();
        dispatch($retry);

        $this->info('Retrying container activations.');
    }
}
