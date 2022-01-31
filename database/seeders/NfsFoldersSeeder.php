<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{ Container, NfsFolder };

class NfsFoldersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Container::all() as $container) {
            NfsFolder::factory()
                ->for($container)
                ->count(rand(1, 10))
                ->create();
        }
    }
}
