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
     * The assigned role name after user's creation
     * 
     * @var string
     */
    private $role = 'user';

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        $role = $this->role;

        return $this->afterMaking(function (User $user) {
            if ($user->email && (! $user->username)) {
                $email = $user->email;
                $user->username = emailToUsername($email);
            }

            if (! $user->username) {
                $faker = $this->faker;
                $user->username = $faker->userName() . $faker->userName();
            }
        })->afterCreating(function (User $user) use ($role) {
            $user->assignRole($role);
        });
    }

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
            // 'username' => $faker->userName() . $faker->userName(),
            'email' => $faker->userName() . $faker->safeEmail(),
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

    /**
     * Indicate that the model's user will be assigned with role of administrator
     * after the model creation.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function administrator()
    {
        $this->role = 'administrator';

        return $this;
    }
}
