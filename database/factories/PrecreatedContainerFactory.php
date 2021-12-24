<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Region, PrecreatedContainer, Order };
use App\Enums\PrecreatedContainer\PrecreatedContainerStatus as Status;

class PrecreatedContainerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PrecreatedContainer::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (PrecreatedContainer $preContainer) {
            if (! $preContainer->order_id) {
                $order = Order::factory()->create();
                $preContainer->order_id = $order->id;
            }

            if (! $preContainer->user_id) {
                $order = Order::findOrFail($preContainer->order_id);
                $preContainer->user_id = $order->user_id;
            }
        })->afterCreating(function (PrecreatedContainer $preContainer) {
            $order = Order::findOrFail($preContainer->order_id);
            foreach ($order->items as $item) {
                $preContainer->applyService($item);
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
            'status' => Status::Prepared,
            'precreated_container_data' => [
                'meta_container' => [
                    'region_id' => Region::first()->id,
                    'hostname' => 'www.hostname.com',
                    'client_email' => $this->faker->safeEmail(),
                ],
                'meta_container_properties' => [],
                'meta_container_additional_properties' => [],
            ],
        ];
    }
}
