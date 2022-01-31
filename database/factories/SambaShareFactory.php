<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ SambaShare, Container };

class SambaShareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaShare::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaShare $sambaShare) {
            if (! $sambaShare->container_id) {
                $container = Container::first() ?: 
                    Container::factory()->create();
                $sambaShare->container_id = $container->id;
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
            'share_name' => random_string(),
            'config_content' => [
                'config1' => random_string(),
                'config2' => random_string(),
                'config3' => random_string(),
            ],
            'permissions' => 'pwr',
        ];
    }
}
