<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
            // CountriesSeeder::class,
            CurrenciesSeeder::class,

            // Settings
            SettingsSeeder::class,

            UsersSeeder::class,

            // Service Plans and Addons
            // ServicePlansSeeder::class,
            // ServiceAddonsSeeder::class,

            // Role and Permissions
            RolesSeeder::class,
            PermissionsSeeder::class,

            // Payments
            // PaymentsSeeder::class,

            // Newsletter
            // NewslettersSeeder::class,
        ];

        if (env('DB_SERVICE_PLAN_SEEDER', false) == true) {
            $seeders[] = ServicePlansSeeder::class;
            $seeders[] = ServiceAddonsSeeder::class;
            $seeders[] = PricingsSeeder::class;
        }

        // Server Management
        if (env('DB_SERVER_SEEDER', true) == true) {
            $seeders = array_merge($seeders, [
                RegionsSeeder::class,
                DatacentersSeeder::class,
                ServersSeeder::class,
                SubnetsSeeder::class,
                // OrdersSeeder::class,
                // ContainersSeeder::class,
                // CommandHistroiesSeeder::class,
            ]);
        }

        if (env('DB_ORDER_CONTAINER_SEEDER', false) == true) {
            $seeders[] = OrdersSeeder::class;
            $seeders[] = ContainersSeeder::class;
            $seeders[] = VpnUsersSeeder::class;
            $seeders[] = VpnPidNumbersSeeder::class;
            $seeders[] = NfsExportsSeeder::class;
        }

    	$this->call($seeders);
    }
}
