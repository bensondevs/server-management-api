<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\PermissionRepository;

use App\Models\User;

class RolesSeeder extends Seeder
{
	protected $permission;

	public function __construct(PermissionRepository $permissionRepository)
	{
		$this->permission = $permissionRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Create Administrator Role
    	$permission = $this->permission->createRole('administrator');

        $admin = User::all()->first();
        $admin->assignRole('administrator');
    }
}
