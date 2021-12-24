<?php

namespace Tests\Feature\Api\Container;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\{ User, Container };

class ContainerTest extends TestCase
{
    /**
     * Populate all user containers test.
     *
     * @return void
     */
    public function test_view_all_containers()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/containers';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('containers');
        });
    }

    /**
     * Check user has container test
     * 
     * @return void
     */
    public function test_check_container_exists()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/containers/exists';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('exists');
        });
    }

    /**
     * Get user current container test
     * 
     * @return void
     */
    public function test_get_current_container()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/containers/current';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('container');
        });
    }

    /**
     * Show container detail test
     * 
     * @return void
     */
    public function test_show_container()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('container');
        });
    }

    /**
     * Select container test
     * 
     * @return void
     */
    public function test_select_container()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/select';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
