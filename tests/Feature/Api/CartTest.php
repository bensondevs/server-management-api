<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Cart, ServicePlan };

class CartTest extends TestCase
{
    /**
     * Populate carts test.
     *
     * @return void
     */
    public function test_populate_carts()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/carts';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('carts');
        });
    }

    /**
     * Add item to cart test
     * 
     * @return void
     */
    public function test_add_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/carts/add';
        $response = $this->json('POST', $url, [
            'cartable_type' => ServicePlan::class,
            'cartable_id' => ServicePlan::first()->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Set quantity to a cart test
     * 
     * @return void
     */
    public function test_set_cart_quantity()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->for($user)->create();

        $url = '/api/carts/' . $cart->id . '/set_quantity';
        $response = $this->json('PATCH', $url, ['quantity' => 2]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove item from cart test
     * 
     * @return void
     */
    public function test_remove_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->for($user)->create();
        $url = '/api/carts/' . $cart->id . '/remove';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Checkout cart test
     * 
     * @return void
     */
    public function test_checkout_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->for($user)->create();
        $url = '/api/carts/checkout';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
