<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Enums\Subscription\SubscriptionStatus as Status;
use App\Models\{ Subscription, ServicePlan, Container, User };

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Subscription $subscription) {
            if (! $subscription->user_id) {
                $user = User::factory()->create();
                $subscription->user_id = $user->id;
            }

            if (! $subscription->subscribeable_id) {
                $plan = ServicePlan::first();
                $subscription->subscribeable_id = $plan->id;
                $subscription->subscribeable_type = ServicePlan::class;
            }

            if (! $subscription->subscriber_id) {
                $subscriber = Container::factory()->create();
                $subscription->subscriber_id = $subscriber->id;
                $subscription->subscriber_type = Container::class;
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
            'status' => Status::Active,
            'start' => $faker->dateTime(),
            'end' => $faker->dateTime(),
        ];
    }

    /**
     * Set subscribeable
     * 
     * @param  mixed  $subscribeable
     * @return $this
     */
    public function subscribeable($subscribeable)
    {
        return $this->state(function (array $attributes) use ($subscribeable) {
            return [
                'subscribeable_type' => get_class($subscribeable),
                'subscribeable_id' => $subscribeable->id,
            ];
        });
    }

    /**
     * Set subscriber
     * 
     * @param  mixed  $subscriber
     * @return $this
     */
    public function subscriber($subscriber)
    {
        return $this->state(function (array $attributes) use ($subscriber) {
            return [
                'subscriber_type' => get_class($subscriber),
                'subscriber_id' => $subscriber->id,
            ];
        });
    }
}