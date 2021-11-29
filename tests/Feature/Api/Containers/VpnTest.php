<?php

namespace Tests\Feature\Api\Containers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

use App\Models\User;
use App\Models\Container;

class VpnTest extends TestCase
{
    /**
     * A check container VPN test.
     *
     * @return void
     */
    public function test_check_container_vpn()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $container = $user->containers()->first();
        $url = '/api/containers/vpn?container_id=' . $container->id;
        /*$response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('vpn_informations');
            $json->has('vpn_informations.vpn_status');
            $json->has('vpn_informations.vpn_pid_numbers');
        });*/
    }

    /**
     * A create container VPN user test.
     *
     * @return void
     */
    public function test_create_vpn_user()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $container = $user->containers()->first();
        $url = '/api/containers/vpn/users/create';
        $response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
            'username' => 'testusername',
            'subnet_ip' => '10.0.8.0/24',
        ]);

        $response->assertStatus(200);
    }
}
