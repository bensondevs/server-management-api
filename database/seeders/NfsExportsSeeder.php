<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Container, NfsExport };

class NfsExportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Container::with('nfsFolders')->get() as $container) {
            foreach ($container->nfsFolders as $nfsFolder) {
                NfsExport::factory()
                    ->for($container)
                    ->for($nfsFolder)
                    ->count(rand(2, 5))
                    ->create();
            }
        }
    }
}
