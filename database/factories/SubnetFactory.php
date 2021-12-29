<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Subnet, Datacenter };
use App\Enums\Subnet\SubnetStatus as Status;

class SubnetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subnet::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Subnet $subnet) {
            if (! $subnet->datacenter_id) {
                $datacenter = Datacenter::factory()->create();
                $subnet->datacenter_id = $datacenter->id;
            }
        })->afterCreating(function (Subnet $subnet) {
            if (! $subnet->ips()->exists()) {
                $subnet->generateIps();
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
            'status' => Status::Available,
            'subnet_mask' => mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '/24'
        ];
    }

    /**
     * Indicate that current subnet status is unavailable
     * 
     * @return $this
     */
    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Unavailable];
        });
    }

    /**
     * Indicate that current subnet status is available
     * 
     * @return $this
     */
    public function available()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Available];
        });
    }

    /**
     * Indicate that current subnet status is forbidden
     * 
     * @return $this
     */
    public function forbidden()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Forbidden];
        });
    }
}
