<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Container, SambaUser, SambaGroup, SambaGroupUser };

class SambaUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaUser::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaUser $sambaUser) {
            if (! $sambaUser->container_id) {
                $container = Container::first() ?:
                    Container::factory()->create();
                $sambaUser->container_id = $container->id;
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
            'id' => generateUuid(),
            'username' => $faker->userName(),
            'password' => encryptString(random_string(5)),
        ];
    }
}
