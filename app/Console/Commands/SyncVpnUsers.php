<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\Container\Vpn\SyncContainerVpnUsers;

class SyncVpnUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:vpnusers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize the VPN User in database and in server.';

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
        $job = new SyncContainerVpnUsers();
        dispatch($job);

        return $this->info('Syncronizing the VPN User in database and in server...');
    }
}
