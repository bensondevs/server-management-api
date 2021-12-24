<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Order, OrderItem, User, PrecreatedContainer };
use App\Enums\Currency;
use App\Enums\Order\OrderStatus as Status;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Order $order) {
            /**
             * Assign user if no user is attached
             */
            if (! $order->user_id) {
                $user = User::factory()->create();
                $order->user_id = $user->id;
            }
        })->afterCreating(function (Order $order) {
            /**
             * Create massive order items if not exists
             */
            if (! $order->items()->exists()) {
                // Add service plan
                OrderItem::factory()
                    ->for($order)
                    ->servicePlan()
                    ->create();

                // Add service addon
                OrderItem::factory()
                    ->for($order)
                    ->serviceAddon()
                    ->count(1)
                    ->create();
            }

            /**
             * Create precreated container
             */
            if (! $order->precreatedContainer()->exists()) {
                PrecreatedContainer::factory()->for($order)->create();
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
            'order_number' => $faker->randomNumber(4, true),
            'status' => Status::Unpaid,

            'currency' => Currency::EUR,
            'total' => $faker->randomNumber(3, false),
            'vat_size_percentage' => $faker->randomNumber(2, false),
            'grand_total' => $faker->randomNumber(3, false),
        ];
    }
}
