<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\{ User };
use App\Enums\User\UserAccountType as AccountType;
use Faker\Factory as Faker;

class AuthTest extends TestCase
{
    /**
     * Login execution test.
     *
     * @return void
     */
    public function test_login()
    {
        $password = 'examplePassword';
        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $url = '/api/auth/login';
        $response = $this->json('POST', $url, [
            'identity' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('user')->etc();
        });
    }

    /**
     * Register test
     * 
     * @return void
     */
    public function test_register()
    {
        $faker = Faker::create();

        $url = '/api/auth/register';
        $response = $this->json('POST', $url, [
            'account_type' => AccountType::Personal,

            'first_name' => $faker->firstName(),
            'middle_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),

            'country' => $faker->country(),
            'address' => $faker->address(),
            'vat_number' => (string) $faker->randomNumber(),

            'username' => $faker->userName(),
            'email' => $faker->safeEmail(),
            'password' => '@UserPasword123',
            'confirm_password' => '@UserPasword123',

            'company_name' => $faker->company(),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Logout test
     * 
     * @return void
     */
    public function test_logout()
    {
        $user = User::first() ?: User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $url = '/api/auth/logout';
        $response = $this->json('POST', $url);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
