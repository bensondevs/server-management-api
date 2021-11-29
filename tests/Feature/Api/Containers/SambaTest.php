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

class SambaTest extends TestCase
{
    /**
     * A check samba test feature.
     *
     * @return void
     */
    public function test_example()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $container = $user->containers()->first();
        $url = '/api/containers/samba?container_id=' . $container->id;
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_informations');
            $json->has('samba_informations.samba_status');
            $json->has('samba_informations.samba_start_on_boot_status');
            $json->has('samba_informations.samba_pid_numbers');
            $json->has('samba_informations.samba_groups');
            $json->has('samba_informations.samba_shares');
        });
    }
}
