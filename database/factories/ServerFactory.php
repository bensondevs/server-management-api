<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Server, Datacenter };
use App\Enums\Server\ServerStatus as Status;

class ServerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Server::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Server $server) {
            if (! $server->datacenter_id) {
                $datacenter = Datacenter::factory()->create();
                $server->datacenter_id = $datacenter->id;
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
            'server_name' => $faker->lastName() . $faker->lastName(),
            'status' => Status::Active,
        ];
    }

    /**
     * Indicate that current server status is active
     * 
     * @return $this
     */
    public function active()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Active];
        });
    }

    /**
     * Indicate that current server status is inactive
     * 
     * @return $this
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Inactive];
        });
    }
}
