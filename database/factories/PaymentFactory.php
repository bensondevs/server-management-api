<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{ 
    Payment,
    SebPayment,
    PaypalPayment,
    StripePayment, 
    User, 
    Order 
};
use App\Enums\Payment\{
    PaymentStatus as Status, 
    PaymentMethod as Method
};

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Payment $payment) {
            if (! $payment->user_id) {
                $user = User::first() ?: User::factory()->create();
                $payment->user_id = $user->id;
            }

            if (! $payment->order_id) {
                $user = User::findOrFail($payment->user_id);
                $order = Order::factory()->for($user)->create();
                $payment->order_id = $order->id;
            }
        })->afterCreating(function (Payment $payment) {
            $user = $payment->user;

            switch ($payment->method) {
                case Method::SEB:
                    SebPayment::factory()
                        ->for($payment)
                        ->setOrderRef($payment->order)
                        ->create(['order_reference' => $payment->order_id]);
                    break;

                case Method::Paypal:
                    PaypalPayment::factory()
                        ->for($payment)
                        ->create();
                    break;

                case Method::Stripe:
                    StripePayment::factory()
                        ->for($payment)
                        ->create();
                    break;
                
                default:
                    SebPayment::factory()
                        ->for($payment)
                        ->setOrderRef($payment->order)
                        ->create(['order_reference' => $payment->order_id]);
                    break;
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
            'method' => Method::SEB,
            'amount' => $this->faker->randomNumber(3, false),
            'status' => Status::Unpaid,
        ];
    }

    /**
     * Indicate that current payment has method of SEB
     * 
     * @return $this
     */
    public function seb()
    {
        return $this->state(function (array $attributes) {
            return ['method' => Method::SEB];
        });
    }
    
    /**
     * Indicate that current payment has method of Paypal
     * 
     * @return $this
     */
    public function paypal()
    {
        return $this->state(function (array $attributes) {
            return ['method' => Method::Paypal];
        });
    }

    /**
     * Indicate that current payment has method of Stripe
     * 
     * @return $this
     */
    public function stripe()
    {
        return $this->state(function (array $attributes) {
            return ['method' => Method::Stripe];
        });
    }

    /**
     * Indicate that current payment has status of Unpaid
     * 
     * @return $this
     */
    public function unpaid()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Unpaid];
        });
    }

    /**
     * Indicate that current payment has status of Settled
     * 
     * @return $this
     */
    public function settled()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Settled];
        });
    }

    /**
     * Indicate that current payment has status of Failed
     * 
     * @return $this
     */
    public function failed()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Failed];
        });
    }

    /**
     * Indicate that current payment has status of Refunded
     * 
     * @return $this
     */
    public function refunded()
    {
        return $this->state(function (array $attributes) {
            return ['status' => Status::Refunded];
        });
    }
}