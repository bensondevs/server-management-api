<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Datacenter, Server };
use App\Enums\Server\ServerStatus;

class ServersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datacenter = Datacenter::first();
        $server = new Server([
            'datacenter_id' => $datacenter->id,
            'server_name' => 'server1.diskray.com',
            'status' => ServerStatus::Active,
        ]);
        $server->ip_address = '185.111.182.2';
        $server->save();
    }
}
