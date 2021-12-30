<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Container, VpnUser };

class VpnUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VpnUser::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (VpnUser $vpnUser) {
            if (! $vpnUser->container_id) {
                $container = Container::first() ?:
                    Container::factory()->create();
                $vpnUser->container_id = $container->id;
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
            'username' => $faker->userName(),
            'config_content' => json_encode([
                'example' => 'example data',
                'config' => 'example config',
            ]),
            'vpn_subnet' => random_subnet(),
            'assigned_subnet_ip' => random_ip(),
        ];
    }
}
