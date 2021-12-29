<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Subscription };

class SubscriptionTest extends TestCase
{
    /**
     * A populate subscription test.
     *
     * @return void
     */
    public function test_view_all_subscriptions()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/subscriptions';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('subscriptions');
        });
    }

    /**
     * A show subscription test
     * 
     * @return void
     */
    public function test_view_subscription()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()->for($user)->create();

        $url = '/api/subscriptions/' . $subscription->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('subscription');
        });
    }

    /**
     * A renew subscription test
     * 
     * @return void
     */
    public function test_renew_subscription()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscription = Subscription::factory()
            ->for($user)
            ->create();

        $url = '/api/subscriptions/' . $subscription->id . '/renew';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A renew multiple subscriptions test
     * 
     * @return void
     */
    public function test_renew_multiple_subscriptions()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $subscriptions = Subscription::factory()
            ->for($user)
            ->count(5)
            ->create();
        $ids = [];
        foreach ($subscriptions as $subscription) {
            array_push($ids, $subscription->id);
        }

        $url = '/api/subscriptions/renew_multiple';
        $response = $this->json('POST', $url, [
            'subscription_ids' => $ids,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
