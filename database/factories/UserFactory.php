<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\User\UserAccountType as Type;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'account_type' => Type::Personal,
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->name(),
            'last_name' => $faker->lastName(),
            'country' => $faker->country(),
            'address' => $faker->address(),
            'vat_number' => $faker->randomNumber(8, false),
            'username' => $faker->userName(),
            'email' => $faker->safeEmail(),
            'password' => bcrypt('password'),
            'company_name' => $faker->company(),
            'subscribe_newsletter' => rand(0, 1),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
