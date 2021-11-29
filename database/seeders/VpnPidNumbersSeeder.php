<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

use App\Models\Container;

class VpnPidNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::all();

        $cache = [];
        foreach ($containers as $container) {
            $pidNumbers = [];
            for ($index = 0; $index < rand(2, 5); $index++) {
                $pidNumbers[] = rand(1000, 9999);
            }

            $cache[$container->id] = $pidNumbers;
        }
        Cache::put('vpn_pid_numbers', $cache, 300);
    }
}
