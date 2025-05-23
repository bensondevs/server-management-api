<?php

namespace Tests\Feature\Api\Container;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Container, VpnUser, VpnSubnet };
use App\Jobs\Container\Vpn\{
    // VPN Actions
    CompleteVpnCheck,
    StartVpn,
    RestartVpn,
    ReloadVpn,
    StopVpn,
    EnableVpn,
    DisableVpn,

    // VPN User
    CreateVpnUser,
    RevokeVpnUser,

    // VPN Configuration
    DownloadVpnConfig,

    // VPN Subnet
    ChangeVpnSubnet,
    ChangeVpnUserSubnetIp
};

class VpnTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A check container VPN test.
     *
     * @return void
     */
    public function test_complete_check_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('vpn_informations');
            $json->has('vpn_informations.vpn_status');
            $json->has('vpn_informations.vpn_enability');
            $json->has('vpn_informations.vpn_pid_numbers');
        });

        Queue::assertPushed(CompleteVpnCheck::class);
    }

    /**
     * A start container VPN test
     * 
     * @return void
     */
    public function test_start_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/start';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_status');
        });

        Queue::assertPushed(StartVpn::class);
    }

    /**
     * A reload container VPN test
     * 
     * @return void
     */
    public function test_reload_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/reload';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_status');
        });

        Queue::assertPushed(ReloadVpn::class);
    }

    /**
     * A restart container VPN test
     * 
     * @return void
     */
    public function test_restart_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/restart';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_status');
        });

        Queue::assertPushed(RestartVpn::class);
    }

    /**
     * A stop container VPN test
     * 
     * @return void
     */
    public function test_stop_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/stop';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_status');
        });

        Queue::assertPushed(StopVpn::class);
    }

    /**
     * An enable container VPN test
     * 
     * @return void
     */
    public function test_enable_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/enable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_enability');
        });

        Queue::assertPushed(EnableVpn::class);
    }

    /**
     * A disable container VPN test
     * 
     * @return void
     */
    public function test_disable_vpn()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/disable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('status')
                ->has('message')
                ->has('vpn_enability');
        });

        Queue::assertPushed(DisableVpn::class);
    }

    /**
     * A populate container VPN user test
     * 
     * @return void
     */
    public function test_populate_vpn_users()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/users';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('vpn_users');
        });
    }

    /**
     * A create container VPN user test.
     *
     * @return void
     */
    public function test_create_vpn_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/vpn/users/create';
        $response = $this->json('POST', $url, [
            'username' => random_string() . random_string(),
            'subnet_ip' => random_subnet(),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
        });

        Queue::assertPushed(CreateVpnUser::class);
    }

    /**
     * A show container VPN user test
     * 
     * @return void
     */
    public function test_show_vpn_user()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $vpnUser = VpnUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/vpn/users/' . $vpnUser->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('vpn_user');
        });
    }

    /**
     * A revoke container VPN user test
     * 
     * @return void
     */
    public function test_revoke_vpn_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $vpnUser = VpnUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/vpn/users/' . $vpnUser->id . '/revoke';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RevokeVpnUser::class);
    }

    /**
     * A download config container VPN user test
     * 
     * @return void
     */
    public function test_download_vpn_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $vpnUser = VpnUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/vpn/users/' . $vpnUser->id . '/download_config';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('config');
        });

        Queue::assertPushed(DownloadVpnConfig::class);
    }
}
