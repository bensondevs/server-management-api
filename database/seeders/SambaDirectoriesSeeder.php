<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaDirectory };

class SambaDirectoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::all();

        $rawDirectories = [];
        foreach ($containers as $container) {
            for ($index = 0; $index < 10; $index++) {
                array_push($rawDirectories, [
                    'id' => generateUuid(),
                    'container_id' => $container->id,
                    'directory_name' => $container->id . '_directory_' . $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        SambaDirectory::insert($rawDirectories);
    }
}
