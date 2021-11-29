<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaShare };

class SambaSharesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::with(['sambaDirectories', 'sambaUsers'])->get();

        $rawShares = [];
        foreach ($containers as $container) {
            foreach ($container->sambaDirectories as $directory) {
                foreach ($container->sambaUsers as $user) {
                    for ($index = 0; $index < 3; $index++) {
                        array_push($rawShares, [
                            'id' => generateUuid(),
                            'container_id' => $container->id,
                            'samba_directory_id' => $directory->id,
                            'samba_user_id' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } 
            }
        }
        SambaShare::insert($rawShares);
    }
}
