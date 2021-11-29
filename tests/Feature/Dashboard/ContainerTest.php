<?php

namespace Tests\Feature\Dashboard;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\{
    User, ServicePlan, Server, Subnet
};

class ContainerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A containers index page.
     *
     * @return void
     */
    public function test_index_containers()
    {
        $user = User::whereHas('roles')->first();
        $url = '/dashboard/containers';
        $response = $this->actingAs($user, 'web')->get($url);

        $response->assertStatus(200);
    }

    /**
     * A containers index page.
     *
     * @return void
     */
    public function test_create_container()
    {
        $user = User::whereHas('roles')->first();
        $url = '/dashboard/containers/create';
        $response = $this->actingAs($user, 'web')->get($url);
        
        $response->assertStatus(200);
    }

    /**
     * A store container request.
     *
     * @return void
     */
    public function test_store_container()
    {
        $user = User::whereHas('roles')->first();
        $url = route('dashboard.containers.store');

        $plan = ServicePlan::inRandomOrder()->first();
        $customer = User::inRandomOrder()->first();
        $server = Server::inRandomOrder()->first();
        $subnet = Subnet::first();
        $ip = $subnet->ips()->assignable()->first();
        
        $this->followingRedirects()->actingAs($user, 'web')->post($url, [
            'service_plan_id' => $plan->id,
            'customer_id' => $customer->id,
            'server_id' => $server->id,
            'subnet_id' => $subnet->id,
            'subnet_ip_id' => $ip->id,
            'hostname' => 'www.test.com',
            'client_email' => $user->email,
            'order_date' => now(),
            'activation_date' => now(),
            'expiration_date' => now()->addDays(50),
        ])->assertSessionHasNoErrors();
    }

    /**
     * A store container request.
     *
     * @return void
     */
}
