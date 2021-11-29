<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaUser };

class SambaUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::all();

        $rawUsers = array();
        foreach ($containers as $container) {
            for ($index = 0; $index < 10; $index++) {
                array_push($rawUsers, [
                    'id' => generateUuid(),
                    'container_id' => $container->id,
                    'username' => 'username_' . $container->id . '_' . ($index + 1),
                    'password' => encryptString('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        SambaUser::insert($rawUsers);
    }
}
