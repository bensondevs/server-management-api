<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, SambaGroup };

class SambaGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $containers = Container::all();

        $rawGroups = array();
        foreach ($containers as $container) {
            for ($index = 0; $index < 10; $index++) {
                array_push($rawGroups, [
                    'id' => generateUuid(),
                    'container_id' => $container->id,
                    'group_name' => 'Group' . $container->id . '#' . ($index + 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        SambaGroup::insert($rawGroups);
    }
}
