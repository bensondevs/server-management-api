<?php

namespace Tests\Feature\Api\Payment;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Enums\Payment\{
    PaymentMethod as Method,
    PaymentStatus as Status
};
use App\Models\{ User, Order, Payment, Container };

class PaymentTest extends TestCase
{
    /**
     * Populate all payments test.
     *
     * @return void
     */
    public function test_view_all_payments()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/payments';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payments');
        });
    }

    /**
     * Create payment from order test
     * 
     * @return void
     */
    public function test_create_payment()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $order = Order::factory()->for($user)->create();

        $url = '/api/payments/create/' . $order->id;
        $response = $this->json('POST', $url, ['method' => Method::SEB]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('payment');
            $json->has('payment.vendor_payment');
        });
    }

    /**
     * Show payment data test
     * 
     * @return void
     */
    public function test_show_payment()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $payment = Payment::factory()->for($user)->create();

        $url = '/api/payments/' . $payment->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('payment');
        });
    }

    /**
     * On payment paid test
     * 
     * @return void
     */
    public function test_on_payment_paid()
    {
        $payment = Payment::factory()->create();
        $order = $payment->order;

        /**
         * Check after status become settled already
         */
        $payment->status = Status::Settled;
        $payment->save();

        $preContainer = $order->precreatedContainer;
        $metaContainer = $preContainer->meta_container;
        $this->assertDatabaseHas('containers', [
            'user_id' => $payment->user_id,
            'region_id' => $metaContainer['region_id'],
            'hostname' => $metaContainer['hostname'],
            'client_email' => $metaContainer['client_email'],
        ]);

        $container = $preContainer->container;
        $this->assertDatabaseHas('subscriptions', [
            'subscriber_type' => Container::class,
            'subscriber_id' => $container->id,
        ]);
    }
}
