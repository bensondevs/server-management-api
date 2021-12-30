<?php

namespace Tests\Feature\Api\Container;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Container, NginxLocation };
use App\Jobs\Container\Nginx\{
    // NGINX action job classes
    StartNginx,
    RestartNginx,
    StopNginx,
    ReloadNginx,
    EnableNginx,
    DisableNginx,
    CompleteNginxCheck,

    // NGINX Location
    Location\CreateNginxLocation,
    Location\RemoveNginxLocation
};

class NginxTest extends TestCase
{
    /**
     * A check container NGINX test.
     *
     * @return void
     */
    public function test_complete_check_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nginx_informations')
                ->has('nginx_informations.nginx_status')
                ->has('nginx_informations.nginx_enability')
                ->has('nginx_informations.nginx_pid_numbers')
                ->has('nginx_informations.nginx_locations');
        });

        Queue::assertPushed(CompleteNginxCheck::class);
    }

    /**
     * A start container NGINX test
     * 
     * @return void
     */
    public function test_start_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/start';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(StartNginx::class);
    }

    /**
     * A restart container NGINX test
     * 
     * @return void
     */
    public function test_restart_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/restart';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RestartNginx::class);
    }

    /**
     * A reload container NGINX test
     * 
     * @return void
     */
    public function test_reload_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/reload';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(ReloadNginx::class);
    }

    /**
     * A stop container NGINX test
     * 
     * @return void
     */
    public function test_stop_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/stop';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(StopNginx::class);
    }

    /**
     * An enable container NGINX start on boot
     * 
     * @return void
     */
    public function test_enable_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/enable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_enability');
            $json->where('status', 'success');
        });

        Queue::assertPushed(EnableNginx::class);
    }

    /**
     * A disable container NGINX start on boot
     * 
     * @return void
     */
    public function test_disable_nginx()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/disable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('nginx_enability');
            $json->where('status', 'success');
        });

        Queue::assertPushed(DisableNginx::class);
    }

    /**
     * A populate container NGINX test
     * 
     * @return void
     */
    public function test_populate_nginx_locations()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/locations';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nginx_locations');
        });
    }

    /**
     * A create container NGINX test
     * 
     * @return void
     */
    public function test_create_nginx_location()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nginx/locations/create';
        $response = $this->json('POST', $url, [
            'nginx_location' => random_string(7),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(CreateNginxLocation::class);
    }

    /**
     * A remove container NGINX test
     * 
     * @return void
     */
    public function test_remove_nginx_location()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $location = NginxLocation::factory()
            ->for($container)
            ->create();
        $url = '/api/containers/' . $container->id . '/nginx/locations/' . $location->id . '/remove';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RemoveNginxLocation::class);
    }
}
