<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ User, Role };

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Role::create(['name' => 'administrator']);
        Role::create(['name' => 'user']);
    }
}
