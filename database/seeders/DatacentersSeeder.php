<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Region, Datacenter };
use App\Enums\Datacenter\DatacenterStatus;

class DatacentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Datacenter::create([
            'region_id' => Region::first()->id,
            'datacenter_name' => 'Lithuania',
            'client_datacenter_name' => 'Lithuania',
            'location' => 'Lithuania',
            'status' => DatacenterStatus::Active,
        ]);
    }
}