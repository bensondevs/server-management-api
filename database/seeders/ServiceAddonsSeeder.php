<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ServiceAddon;

use App\Enums\Currency;
use App\Enums\ContainerProperty\ContainerPropertyType as Type;

class ServiceAddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$diskSizeAddon = ServiceAddon::factory()->create([
            'addon_name' => '1 Month extra 10 GB Disk Size',
            'addon_code' => sluggify('Extra 10 GB Disk Size'),
            'description' => 'Additioal 10GB of service disk size.',

            'duration_days' => 30,
            'property_type' => Type::DiskSize,
            'property_value' => 10,
        ]);
        $diskSizeAddon->setPrice(10, Currency::EUR);
        $diskSizeAddon->setPrice(12, Currency::USD);

        $diskArrayAddon = ServiceAddon::factory()->create([
            'addon_name' => '1 Month extra 10 Disk Array',
            'addon_code' => sluggify('Extra 10 Disk Array'),
            'description' => 'Additioal 10 disk array.',

            'duration_days' => 30,
            'property_type' => Type::DiskArray,
            'property_value' => 10,
        ]);
        $diskArrayAddon->setPrice(10, Currency::EUR);
        $diskArrayAddon->setPrice(10, Currency::USD);

        $breakpointsAddon = ServiceAddon::factory()->create([
            'addon_name' => '1 Month extra 10 Breakpoints',
            'addon_code' => sluggify('Extra 10 Breakpoints'),
            'description' => 'Additioal 10 breakpoints.',

            'duration_days' => 30,
            'property_type' => Type::Breakpoints,
            'property_value' => 10,
        ]);
        $breakpointsAddon->setPrice(10, Currency::EUR);
        $breakpointsAddon->setPrice(11, Currency::USD);
    }
}