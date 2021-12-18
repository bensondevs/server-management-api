<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Cart, CartItem, ServicePlan, ServiceAddon };

class CartItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CartItem::class;

    /**
     * Configure the model factory
     * 
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (CartItem $item) {
            if (! $item->cart_id) {
                $cart = Cart::factory()->create();
                $item->cart_id = $cart->id;
            }

            if (! $item->cart_itemable_id) {
                rand(0, 1) ? 
                    $this->servicePlan() : 
                    $this->serviceAddon();
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
            'quantity' => 1,
            'sub_total' => 0,
            'discount' => 0,
        ];
    }

    /**
     * Indicate that current cart item holds service plan
     * 
     * @return $this
     */
    public function servicePlan()
    {
        return $this->state(function (array $attributes) {
            $plan = ServicePlan::inRandomOrder()->first();
            $pricing = $plan->pricings()->first();
            return [
                'quantity' => 1,
                'sub_total' => $pricing->price,
                'cart_itemable_type' => ServicePlan::class,
                'cart_itemable_id' => $plan->id,
            ];
        });
    }

    /**
     * Indicate that current cart item holds service addon
     * 
     * @return $this
     */
    public function serviceAddon()
    {
        return $this->state(function (array $attributes) {
            $addon = ServiceAddon::inRandomOrder()->first();
            $pricing = $addon->pricings()->first();
            return [
                'sub_total' => $attributes['quantity'] * $pricing->price,
                'cart_itemable_id' => $addon->id,
                'cart_itemable_type' => ServiceAddon::class,
            ];
        });
    }
}
