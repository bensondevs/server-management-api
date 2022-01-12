<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->call([
            SettingsSeeder::class,
            CountriesSeeder::class,
            
            RolesSeeder::class,
            UsersSeeder::class,

            RegionsSeeder::class,
            DatacentersSeeder::class,
            ServersSeeder::class,
            SubnetsSeeder::class,
            
            ServicePlansSeeder::class,
            ServiceAddonsSeeder::class,

            ContainersSeeder::class,
        ]);
    }
}
