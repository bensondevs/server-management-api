<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\PermissionRepository;

class PermissionsSeeder extends Seeder
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
    	$permissions = [
    		// Authentication
    		'dashboard login',

            // Regions
            'view regions',
            'create regions',
            'edit regions',
            'delete regions',

            // Datacenters
            'toggle datacenters',
            'view datacenters',
            'create datacenters',
            'edit datacenters',
            'delete datacenters',

    		// Servers
            'toggle servers',
    		'view servers',
    		'create servers',
    		'edit servers',
    		'delete servers',

            // Subnets
            'assign subnets',
            'view subnets',
            'create subnets',
            'edit subnets',
            'delete subnets',

            // IP(s)
            'assign ips',
            'view ips',
            'create ips',
            'edit ips',
            'delete ips',

            // Command Histories
            'execute commands',
            'view command histories',

            // Containers
            'view containers',
            'create containers',
            'edit containers',
            'delete containers',

            // Container VPN
            'manage container vpns',
            'create user container vpns',
            'revoke user container vpns',
            'check status container vpns',
            'check pid numbers container vpns',
            'start container vpns',
            'restart container vpns',
            'stop container vpns',
            'toggle start on boot container vpns',
            'download config container vpns',

            // Container NGINX
            'check status container nginxes',
            'check pid numbers container nginxes',
            'create location container nginxes',
            'remove location container nginxes',
            'start container nginxes',
            'restart container nginxes',
            'reload container nginxes',
            'stop container nginxes',

            // Job Trackers
            'view job trackers',
            'delete job trackers',

            // Orders
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',

            // Payments
            'view payments',
            'create payments',
            'edit payments',
            'delete payments',

            // Service Plans
            'view service plans',
            'create service plans',
            'edit service plans',
            'delete service plans',

            // Service Addons
            'view service addons',
            'create service addons',
            'edit service addons',
            'delete service addons',

            // Newsletters
            'view newsletters',
            'create newsletters',
            'edit newsletters',
            'delete newsletters',

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',
    	];

    	foreach ($permissions as $permission) {
    		$permission = $this->permission->createPermission($permission);
    		$permission->assignRole('administrator');
    	}
    }
}
