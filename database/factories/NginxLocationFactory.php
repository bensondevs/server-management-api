<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ NginxLocation, Container };

class NginxLocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NginxLocation::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (NginxLocation $nginxLocation) {
            if (! $nginxLocation->container_id) {
                $container = Container::first() ?: 
                    Container::factory()->create();
                $nginxLocation->container_id = $container->id;
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
            'nginx_location' => random_string(10),
            'nginx_config' => json_encode([
                'config1' => 'example1',
                'config2' => 'example2',
                'config3' => 'example3',
            ]),
        ];
    }
}
