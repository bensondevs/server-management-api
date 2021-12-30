<?php

namespace Tests\Feature\Api\Container;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Container, NfsFolder, NfsExport };
use App\Jobs\Container\Nfs\{
    // NFS action job classes
    StartNfs,
    RestartNfs,
    StopNfs,
    ReloadNfs,
    EnableNfs,
    DisableNfs,
    CompleteNfsCheck,

    // NFS Export job classes
    Export\CreateNfsExport,
    Export\UpdateNfsExport,
    Export\DeleteNfsExport,

    // NFS Folder job classes
    Folder\CreateNfsFolder,
    Folder\DeleteNfsFolder
};

class NfsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A container nfs check information test.
     *
     * @return void
     */
    public function test_check_container_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nfs_informations');
            $json->has('nfs_informations.nfs_status');
            $json->has('nfs_informations.nfs_pid_numbers');
            $json->has('nfs_informations.exports');
            $json->has('nfs_informations.folders');
        });

        Queue::assertPushed(CompleteNfsCheck::class);
    }

    /**
     * A populate container nfs status badges test.
     *
     * @return void
     */
    public function test_populate_nfs_status_badges()
    {
        $url = '/meta/container/nfs_status_badges';
        $response = $this->json('GET', $url);
        $response->assertStatus(200);
    }

    /**
     * A populate container nfs folders test
     * 
     * @return void
     */
    public function test_populate_nfs_folders()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/folders';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nfs_folders');
        });
    }

    /**
     * A create container nfs folder test.
     *
     * @return void
     */
    public function test_create_nfs_folder()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/folders/create';
        $response = $this->json('POST', $url, [
            'container_id' => $container->id,
            'folder_name' => 'Test folder Name example',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(CreateNfsFolder::class);
    }

    /**
     * A delete container nfs folder test.
     *
     * @return void
     */
    public function test_delete_nfs_folder()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $folder = NfsFolder::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/nfs/folders/' . $folder->id . '/delete';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(DeleteNfsFolder::class);
    }

    /**
     * A populate container nfs exports test
     * 
     * @return void
     */
    public function test_populate_nfs_exports()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/exports';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nfs_exports');
        });
    }

    /**
     * A create container nfs export test.
     *
     * @return void
     */
    public function test_create_nfs_export()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $folder = NfsFolder::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/nfs/exports/create';

        $response = $this->json('POST', $url, [
            'nfs_folder_id' => $folder->id,
            'ip_address' => '1.11.11.1',
            'permissions' => 'rw',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(CreateNfsExport::class);
    }

    /**
     * A delete container nfs export test.
     *
     * @return void
     */
    public function test_delete_nfs_export()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $nfsExport = NfsExport::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/nfs/exports/' . $nfsExport->id . '/delete';
        $response = $this->json('DELETE', $url);
        
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(DeleteNfsExport::class);
    }

    /**
     * A start container nfs test.
     *
     * @return void
     */
    public function test_start_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/start';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_status');
        });

        Queue::assertPushed(StartNfs::class);
    }

    /**
     * A restart container nfs test.
     *
     * @return void
     */
    public function test_restart_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/restart';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_status');
        });

        Queue::assertPushed(RestartNfs::class);
    }

    /**
     * A reload container nfs test
     * 
     * @return void
     */
    public function test_reload_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/reload';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_status');
        });

        Queue::assertPushed(ReloadNfs::class);
    }

    /**
     * A stop container nfs test.
     *
     * @return void
     */
    public function test_stop_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/stop';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_status');
        });

        Queue::assertPushed(StopNfs::class);
    }

    /**
     * A enable container nfs on start boot
     * 
     * @return void
     */
    public function test_enable_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/enable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_enability');
        });

        Queue::assertPushed(EnableNfs::class);
    }

    /**
     * A disable container nfs on start boot
     * 
     * @return void
     */
    public function test_disable_nfs()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/nfs/disable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('nfs_enability');
        });

        Queue::assertPushed(DisableNfs::class);
    }
}
