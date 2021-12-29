<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Order, OrderItem, ServicePlan, ServiceAddon };
use App\Enums\Currency;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (OrderItem $item) {
            if (! $item->order_id) {
                $order = Order::factory()->create();
                $item->order_id = $order->id;
            }

            if (! $item->itemable_id) {
                $itemable = ServicePlan::first();
                $item->itemable_id = $itemable->id;
                $item->itemable_type = ServicePlan::class;
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
            'is_renewal' => false,
            'quantity' => $faker->randomNumber(1, true),
            'currency' => $faker->randomElement([
                Currency::EUR,
                Currency::USD,
            ]),
            'price' => $faker->randomNumber(3, false),
            'discount' => $faker->randomNumber(1, false),
            'total' => $faker->randomNumber(3, false),
            'note' => $faker->word(),
        ];
    }

    /**
     * Indicate that current order item holds service plan
     * 
     * @return $this
     */
    public function servicePlan()
    {
        return $this->state(function (array $attributes) {
            $plan = ServicePlan::first();
            return [
                'itemable_id' => $plan->id,
                'itemable_type' => ServicePlan::class,
            ];
        });
    }

    /**
     * Indicate that current order item holds service addon
     * 
     * @return $this
     */
    public function serviceAddon()
    {
        return $this->state(function (array $attributes) {
            $addon = ServiceAddon::first();
            return [
                'itemable_id' => $addon->id,
                'itemable_type' => ServiceAddon::class,
            ];
        });
    }
}
