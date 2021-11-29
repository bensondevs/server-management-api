<?php

namespace Tests\Feature\Dashboard;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Order;

class OrderTest extends TestCase
{
    /**
     * An index order list.
     *
     * @return void
     */
    public function test_index_orders()
    {
        $user = User::whereHas('roles')->first();
        $url = '/dashboard/orders';
        $response = $this->actingAs($user, 'web')->get($url);

        $response->assertStatus(200);
    }

    /**
     * A create order list.
     *
     * @return void
     */
    public function test_delete_order()
    {
        $user = User::whereHas('roles')->first();
        $order = Order::first();
        $url = '/dashboard/orders/' . $order->id . '/delete';
        $response = $this->actingAs($user, 'web')->get($url);

        $response->assertStatus(200);
    }
}
