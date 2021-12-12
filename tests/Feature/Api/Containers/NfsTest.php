<?php

namespace Tests\Feature\Api\Containers;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;

use App\Models\{ User, Container };

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
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $container = $user->containers()->first();
        $url = '/api/containers/nfs?container_id=' . $container->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('nfs_informations');
            $json->has('nfs_informations.status');
            $json->has('nfs_informations.pid_numbers');
            $json->has('nfs_informations.exports');
        });
    }

    /**
     * A populate container nfs status badges test.
     *
     * @return void
     */
    public function test_populate_nfs_status_badges()
    {
        $headers = [
            'Accept' => 'application/json',
        ];
        $url = '/meta/container/nfs_status_badges';
        $response = $this->withHeaders($headers)->get($url);
        $response->assertStatus(200);
    }

    /**
     * A create container nfs folder test.
     *
     * @return void
     */
    public function test_create_nfs_folder()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/folder/create';
        $container = $user->containers()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
            'folder_name' => 'Test folder Name example',
        ]);

        $response->assertStatus(200);*/
    }

    /**
     * A delete container nfs folder test.
     *
     * @return void
     */
    public function test_delete_nfs_folder()
    {
        $user = User::whereHas('containers.nfsFolders')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/folder/delete';
        $container = $user->containers()->first();
        $folder = $container->nfsFolders()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'nfs_folder_id' => $folder->id,
        ]);

        $response->assertStatus(200);*/
    }

    /**
     * A create container nfs export test.
     *
     * @return void
     */
    public function test_create_nfs_export()
    {
        $user = User::whereHas('containers.nfsFolders')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/export/create';
        $container = $user->containers()->first();
        $folder = $container->nfsFolders()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
            'nfs_folder_id' => $folder->id,
            'ip_address' => '1.11.11.1',
            'permissions' => 'rw',
        ]);

        $response->assertStatus(200);*/
    }

    /**
     * A delete container nfs export test.
     *
     * @return void
     */
    public function test_delete_nfs_export()
    {
        $user = User::whereHas('containers.nfsExports')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/export/delete';
        $container = $user->containers()->first();
        $nfsExport = $container->nfsExports()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'nfs_export_id' => $nfsExport->id,
        ]);
        
        $response->assertStatus(200);*/
    }

    /**
     * A start container nfs export test.
     *
     * @return void
     */
    public function test_start_nfs()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/start';
        $container = $user->containers()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
        ]);

        $response->assertStatus(200);*/
    }

    /**
     * A restart container nfs export test.
     *
     * @return void
     */
    public function test_restart_nfs()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/restart';
        $container = $user->containers()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
        ]);

        $response->assertStatus(200);*/
    }

    /**
     * A stop container nfs export test.
     *
     * @return void
     */
    public function test_stop_nfs()
    {
        $user = User::whereHas('containers')->first();
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/containers/nfs/stop';
        $container = $user->containers()->first();
        /*$response = $this->withHeaders($headers)->post($url, [
            'container_id' => $container->id,
        ]);

        $response->assertStatus(200);*/
    }
}
