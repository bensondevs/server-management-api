<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Container, SambaShare, SambaUser, SambaShareUser };

class SambaShareUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaShareUser::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaShareUser $shareUser) {
            if (! $shareUser->container_id) {
                $container = Container::first() ?: 
                    Container::factory()->create();
                $shareUser->container_id = $container->id;
            }

            if (! $shareUser->samba_share_id) {
                $container = Container::findOrFail($shareUser->container_id);
                $share = SambaShare::factory()->for($container)->create();
                $shareUser->samba_share_id = $share->id;
            }

            if (! $shareUser->samba_user_id) {
                $container = Container::findOrFail($shareUser->container_id);
                $user = SambaUser::factory()->for($container)->create();
                $shareUser->samba_user_id = $user->id;
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
