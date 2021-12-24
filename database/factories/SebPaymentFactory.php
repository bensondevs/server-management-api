<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Order, Payment, SebPayment };
use App\Enums\Payment\Seb\SebPaymentState as PaymentState;

class SebPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SebPayment::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SebPayment $sebPayment) {
            if (! $sebPayment->payment_id) {
                $payment = Payment::factory()->create();
                $sebPayment->payment_id = $payment->id;
            }

            if (! $sebPayment->order_id) {
                $order = Order::factory()->create();
                $sebPayment->order_reference = $order->id;
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
            'payment_state' => PaymentState::Initial,
            'amount' => $faker->randomNumber(3, false),
            'billing_address' => [
                'billing_city' => $faker->city(),
                'billing_country' => $faker->country(),
                'billing_line1' => $faker->address(),
                'billing_line2' => $faker->address(),
                'billing_line3' => $faker->address(),
                'billing_postcode' => $faker->postcode(),
                'billing_state' => $faker->state(),
            ],
        ];
    }

    /**
     * Set order reference of the SEB Payment
     * 
     * @param  \App\Models\Order  $order
     * @return $this
     */
    public function setOrderRef(Order $order)
    {
        return $this->state(function (array $attributes) use ($order) {
            return ['order_reference' => $order->id];
        });
    }
}
