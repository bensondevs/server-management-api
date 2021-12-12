<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Region;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create(['region_name' => 'Europe']);
    }
}