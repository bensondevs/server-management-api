<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Cart, CartItem, ServicePlan, ServiceAddon };

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
     * Populate cart items test
     * 
     * @return void
     */
    public function test_populate_cart_items()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->create();
        $url = '/api/carts/' . $cart->id . '/items';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cart_items');
        });
    }

    /**
     * Add service plan as item to cart
     * 
     * @return void
     */
    public function test_add_service_plan_to_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $plan = ServicePlan::first();
        $url = '/api/carts/add_plan/' . $plan->id;
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status.0', 'success');
            $json->where('status.1', 'success');
        });
    }

    /**
     * Add service addon as item to cart
     * 
     * @return void
     */
    public function test_add_service_addon_to_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->create();
        $addon = ServiceAddon::first();
        $url = '/api/carts/' . $cart->id . '/add_addon/' . $addon->id;
        $response = $this->json('POST', $url, ['quantity' => rand(1, 10)]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Remove item from cart
     * 
     * @return void
     */
    public function remove_item_from_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $item = CartItem::factory()->create();
        $url = '/api/carts/items/' . $item->id;
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

        $cart = Cart::factory()
            ->for($user)
            ->has(CartItem::factory()->count(10), 'items')
            ->create();
        $url = '/api/carts/checkout';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Destroy cart test
     * 
     * @return void
     */
    public function test_destroy_cart()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $cart = Cart::factory()->for($user)->create();
        $url = '/api/carts/' . $cart->id . '/destroy';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
