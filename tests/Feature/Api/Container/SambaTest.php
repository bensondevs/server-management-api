<?php

namespace Tests\Feature\Api\Container;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    User, 
    Container, 
    SambaGroup, 
    SambaUser,
    SambaGroupUser,
    SambaShare,
    SambaShareGroup,
    SambaShareUser
};
use App\Jobs\Container\Samba\{
    CompleteSambaCheck,
    StartSamba,
    StopSamba,
    RestartSamba,
    ReloadSamba,
    EnableSamba,
    DisableSamba
};
use App\Jobs\Container\Samba\User\{
    CreateSambaUser,
    ChangeSambaUserPassword,
    DeleteSambaUser
};
use App\Jobs\Container\Samba\Group\{
    // Basic samba group samba
    CreateSambaGroup,
    DeleteSambaGroup,
    User\AddSambaGroupUser,
    User\RemoveSambaGroupUser
};
use App\Jobs\Container\Samba\Share\{
    // Basic Samba Share Action
    CreateSambaShare,
    DeleteSambaShare,

    // Samba Share Group Action
    Group\AddSambaShareGroup,
    Group\RemoveSambaShareGroup,

    // Samba Share User Action
    User\AddSambaShareUser,
    User\RemoveSambaShareUser
};

class SambaTest extends TestCase
{
    use DatabaseTransactions;

    /*
    |--------------------------------------------------------------------------
    | Samba Basic Action Test
    |--------------------------------------------------------------------------
    |
    | This section of the TestCase class will test all the basic actions of samba
    | service. The actions covered are complete checking, starting, restarting,
    | reloading, stopping, enabling and disabling.
    |
    */
    /**
     * A complete samba check test.
     *
     * @return void
     */
    public function test_complete_check_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_informations')
                ->has('samba_informations.samba_status')
                ->has('samba_informations.samba_enability')
                ->has('samba_informations.samba_pid_numbers')
                ->has('samba_informations.samba_groups')
                ->has('samba_informations.samba_users')
                ->has('samba_informations.samba_shares');
        });

        Queue::assertPushed(CompleteSambaCheck::class);
    }

    /**
     * A start container samba service test
     * 
     * @return void
     */
    public function test_start_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/start';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_status')
                ->has('samba_status.nmbd')
                ->has('samba_status.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(StartSamba::class);
    }

    /**
     * A restart container samba service test
     * 
     * @return void
     */
    public function test_restart_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/restart';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_status')
                ->has('samba_status.nmbd')
                ->has('samba_status.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RestartSamba::class);
    }

    /**
     * A reload container samba service test
     * 
     * @return void
     */
    public function test_reload_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/reload';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_status')
                ->has('samba_status.nmbd')
                ->has('samba_status.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(ReloadSamba::class);
    }

    /**
     * A stop container samba service test
     * 
     * @return void
     */
    public function test_stop_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/stop';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_status')
                ->has('samba_status.nmbd')
                ->has('samba_status.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(StopSamba::class);
    }

    /**
     * An enable container samba service test
     * 
     * @return void
     */
    public function test_enable_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/enable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_enability')
                ->has('samba_enability.nmbd')
                ->has('samba_enability.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(EnableSamba::class);
    }

    /**
     * A disable container samba service test
     * 
     * @return void
     */
    public function test_disable_samba()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/disable';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')
                ->has('message')
                ->has('samba_enability')
                ->has('samba_enability.nmbd')
                ->has('samba_enability.smbd');
            $json->where('status', 'success');
        });

        Queue::assertPushed(DisableSamba::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Samba User Action Test
    |--------------------------------------------------------------------------
    |
    | This section of the TestCase class will test all of samba user features.
    | This section will test on populating samba users, creating samba users, updating
    | samba user's password, and deleting samba user.
    |
    */
    /**
     * A populate samba users test
     * 
     * @return void
     */
    public function test_populate_samba_users()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/users';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_users');
        });
    }

    /**
     * A create samba user test
     * 
     * @return void
     */
    public function test_create_samba_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?: 
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/users/create';
        $response = $this->json('POST', $url, [
            'username' => random_string(6),
            'password' => random_string(10),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(CreateSambaUser::class);
    }

    /**
     * An update samba user's password test
     * 
     * @return void
     */
    public function test_update_samba_user_password()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaUser = SambaUser::factory()
            ->for($container)
            ->create(['unencrypted_password' => ($oldPassword = random_string(10))]);
        $url = '/api/containers/' . $container->id . '/samba/users/' . $sambaUser->id . '/change_password';
        $response = $this->json('PATCH', $url, [
            'old_password' => $oldPassword,
            'password' => ($newPassword = random_string(10)),
            'confirm_password' => $newPassword,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(ChangeSambaUserPassword::class);
    }

    /**
     * A remove samba user test
     * 
     * @return void
     */
    public function test_delete_samba_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaUser = SambaUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/users/' . $sambaUser->id . '/delete';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(DeleteSambaUser::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Samba Group Action Test
    |--------------------------------------------------------------------------
    |
    | This section of the TestCase class will test all of samba group features.
    | This section do testing on actions of populating samba groups, showing samba group,
    | populating samba group users, adding group user, removing group user.
    |
    */
    /**
     * A populate samba groups test
     * 
     * @return void
     */
    public function test_populate_samba_groups()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/groups';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_groups');
        });
    }

    /**
     * A show samba group test
     * 
     * @return void
     */
    public function test_show_samba_group()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaGroup = SambaGroup::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/groups/' . $sambaGroup->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_group');
        });
    }

    /**
     * A populate samba group users test
     * 
     * @return void
     */
    public function test_populate_group_users()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaGroup = SambaGroup::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/groups/' . $sambaGroup->id . '/users/';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_users');
        });
    }

    /**
     * An add samba group user test
     * 
     * @return void
     */
    public function test_add_samba_group_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaGroup = SambaGroup::factory()->for($container)->create();
        $sambaUser = SambaUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/groups/' . $sambaGroup->id . '/users/add/' . $sambaUser->id;
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(AddSambaGroupUser::class);
    }

    /**
     * A remove samba group user test
     * 
     * @return void
     */
    public function test_remove_samba_group_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $sambaGroupUser = SambaGroupUser::factory()->for($container)->create();
        $sambaGroup = $sambaGroupUser->group;
        $sambaUser = $sambaGroupUser->user;
        $url = '/api/containers/' . $container->id . '/samba/groups/' . $sambaGroup->id . '/users/remove/' . $sambaUser->id;
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RemoveSambaGroupUser::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Samba Share Feature Test
    |--------------------------------------------------------------------------
    |
    | This section of the TestCase class will test all the samba share features.
    | This section will test on populating list of samba shares, creating new share,
    | showing share, updating share and deleting share
    |
    */
    /**
     * A populate samba shares test
     * 
     * @return void
     */
    public function test_populate_samba_shares()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_shares');
        });
    }

    /**
     * A create samba share test
     * 
     * @return void
     */
    public function test_create_samba_share()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/create';
        $response = $this->json('POST', $url, [
            'directory_name' => random_string(10),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message')->has('status');
        });

        Queue::assertPushed(CreateSambaShare::class);
    }

    /**
     * A show samba share test
     * 
     * @return void
     */
    public function test_show_samba_share()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_share');
        });
    }

    /**
     * A delete samba share test
     * 
     * @return void
     */
    public function test_delete_samba_share()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/delete';
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
        });

        Queue::assertPushed(DeleteSambaShare::class);
    }

    /**
     * A populate samba share's groups
     * 
     * @return void
     */
    public function test_populate_samba_share_groups()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/groups';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_groups');
        });
    }

    /**
     * An add group to samba share test
     * 
     * @return void
     */
    public function test_add_samba_share_group()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $group = SambaGroup::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/groups/add/' . $group->id;
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
        });

        Queue::assertPushed(AddSambaShareGroup::class);
    }

    /**
     * A remove group from samba share test
     * 
     * @return void
     */
    public function test_remove_samba_share_group()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $shareGroup = SambaShareGroup::factory()
            ->for($container)
            ->create();
        $share = $shareGroup->share;
        $group = $shareGroup->group;
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/groups/remove/' . $group->id;
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
        });

        Queue::assertPushed(RemoveSambaShareGroup::class);
    }

    /**
     * A populate samba share's users
     * 
     * @return void
     */
    public function test_populate_samba_share_users()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/users';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('samba_users');
        });
    }

    /**
     * An add samba share's user
     * 
     * @return void
     */
    public function test_add_samba_share_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $share = SambaShare::factory()->for($container)->create();
        $user = SambaUser::factory()->for($container)->create();
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/users/add/' . $user->id;
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(AddSambaShareUser::class);
    }

    /**
     * A remove samba share's user
     * 
     * @return void
     */
    public function test_remove_samba_share_user()
    {
        Queue::fake();

        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $container = $user->containers()->first() ?:
            Container::factory()->for($user)->create();
        $shareUser = SambaShareUser::factory()->for($container)->create();
        $share = $shareUser->share;
        $user = $shareUser->user;
        $url = '/api/containers/' . $container->id . '/samba/shares/' . $share->id . '/users/remove/' . $user->id;
        $response = $this->json('DELETE', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status')->has('message');
            $json->where('status', 'success');
        });

        Queue::assertPushed(RemoveSambaShareUser::class);
    }
}
