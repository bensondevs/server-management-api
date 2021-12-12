<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ Cart, ServicePlan };

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Cart $cart) {
            if (! $cart->user_id) {
                $user = User::first();
                $cart->user_id = $user->id;
            }

            if (! $cart->cartable_id) {
                $cart->cartable_id = ServicePlan::first()->id;
                $cart->cartable_type = ServicePlan::class;
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
            'quantity' => rand(1, 10),
        ];
    }
}