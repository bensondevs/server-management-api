<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Order };

class OrderTest extends TestCase
{
    /**
     * Populate orders test.
     *
     * @return void
     */
    public function test_view_all_orders()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/orders';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('orders');
        });
    }

    /**
     * Show order test
     * 
     * @return void
     */
    public function test_view_order()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $order = Order::factory()->create();

        $url = '/api/orders/' . $order->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('order');
        });
    }
}
