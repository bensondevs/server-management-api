<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ServiceAddon;
use App\Enums\ServiceAddon\ServiceAddonStatus as Status;
use App\Enums\ContainerProperty\ContainerPropertyType as PropertyType;

class ServiceAddonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceAddon::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (ServiceAddon $addon) {
            //
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
            'addon_name' => $faker->word(),
            'addon_code' => random_string(4),
            'description' => $faker->word(),

            'status' => Status::Active,
            'duration_days' => $faker->randomNumber(1, true),

            'property_type' => PropertyType::DiskSize,
            'property_value' => $faker->randomNumber(3, true),
        ];
    }
}
