<?php

namespace Tests\Feature\Api\Payment;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Payment, SebPayment };

class SebPaymentTest extends TestCase
{
    /**
     * Test execute payment of the SEB payment instance and return
     * payment page url to be redirected.
     *
     * @return void
     */
    public function test_pay()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $sebPayment = SebPayment::factory()->create();

        $url = '/api/payments/seb/' . $sebPayment->id . '/pay';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('seb_payment');
        });
    }

    /**
     * Receive callback from SEB API test
     * 
     * @return void
     */
    public function test_receive_payment_callback()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $payment = Payment::factory()->seb()->create();
        $order = $payment->order;

        $url = '/api/callbacks/payments/seb';
        $response = $this->json('GET', $url, [
            'payment_reference' => $payment->id,
            'order_reference' => $order->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Check payment in SEB API test
     * 
     * @return void
     */
    public function test_payment_check()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        // Make payment
        $sebPayment = SebPayment::factory()->create();
        $createUrl = '/api/payments/seb/' . $sebPayment->id . '/pay';
        $this->json('POST', $createUrl);
            
        $url = '/api/payments/seb/' . $sebPayment->id . '/check';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('seb_payment');
        });
    }

    /**
     * Check SEB Payment instance test
     * 
     * @return void
     */
    public function test_show_payment()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $sebPayment = SebPayment::factory()->create();
        $url = '/api/payments/seb/' . $sebPayment->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('seb_payment');
        });
    }
}
