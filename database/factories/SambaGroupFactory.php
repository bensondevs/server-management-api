<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ SambaGroup, Container };

class SambaGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaGroup::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaGroup $sambaGroup) {
            if (! $sambaGroup->container_id) {
                $container = Container::first() ?: 
                    Container::factory()->create();
                $sambaGroup->container_id = $container->id;
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
        return [
            'group_name' => random_string(7),
        ];
    }
}
