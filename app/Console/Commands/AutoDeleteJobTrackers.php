<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\DeleteJobTrackers;

class AutoDeleteJobTrackers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracker:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear three days job trackers log in database';

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
        $job = new DeleteJobTrackers();
        dispatch($job);

        return $this->info('Deleting job trackers...');
    }
}
