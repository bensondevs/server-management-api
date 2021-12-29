<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Datacenter, Region };

class DatacenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Datacenter::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Datacenter $datacenter) {
            if (! $datacenter->region_id) {
                $region = Region::factory()->create();
                $datacenter->region_id = $region->id;
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;

        return [
            'datacenter_name' => $faker->firstName(),
            'client_datacenter_name' => $faker->lastName(),
            'location' => $faker->country(),
        ];
    }
}
