<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Container;

class ContainersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Container::factory()->create();
    }
}