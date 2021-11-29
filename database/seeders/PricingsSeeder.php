<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ServicePlan;
use App\Models\ServiceAddon;
use App\Models\Pricing;

class PricingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawPricings = [];

        foreach (ServicePlan::all() as $key => $plan) {
            $price = 10 * $key;
            for ($index = 1; $index < 3; $index++) {
                $rawPricings[] = [
                    'id' => generateUuid(),
                    'priceable_id' => $plan->id,
                    'priceable_type' => get_class($plan),
                    'currency' => $index,
                    'price' => $price,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }

        foreach (ServiceAddon::all() as $addon) {
            $price = 10 * $key;
            for ($index = 1; $index < 3; $index++) {
                $rawPricings[] = [
                    'id' => generateUuid(),
                    'priceable_id' => $addon->id,
                    'priceable_type' => get_class($addon),
                    'currency' => $index,
                    'price' => $price,
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ];
            }
        }

        Pricing::insert($rawPricings);
    }
}
