<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Container;
use App\Models\VpnUser;

class VpnUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::all();

        $rawVpnUsers = [];
        foreach ($containers as $key => $container) {
            for ($index = 0; $index < rand(3, 10); $index++) {
                $rawVpnUsers[] = [
                    'id' => generateUuid(),
                    'container_id' => $container->id,
                    'username' => 'user_' . $container->id . '_vpn_' . ($index + 1),
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }
        VpnUser::insert($rawVpnUsers);
    }
}
