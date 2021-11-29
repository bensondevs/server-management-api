<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Container;
use App\Models\NfsExport;

class NfsExportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawNfsExports = [];
        foreach (Container::all() as $key => $container) {
            for ($index = 0; $index < rand(5, 10); $index++) {
                $rawNfsExports[] = [
                    'id' => generateUuid(),
                    'container_id' => $container->id,
                    'target_folder' => 'exporter/test',
                    'permissions' => 'rw,sync,no_root_squash',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }
        NfsExport::insert($rawNfsExports);
    }
}
