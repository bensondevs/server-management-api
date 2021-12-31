<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ Container, SambaGroup, SambaUser, SambaGroupUser };

class SambaGroupUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaGroupUser::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaGroupUser $groupUser) {
            if (! $groupUser->container_id) {
                $container = Container::first() ?: 
                    Container::factory()->create();
                $groupUser->container_id = $container->id;
            }

            if (! $groupUser->samba_group_id) {
                $container = Container::findOrFail($groupUser->container_id);
                $group = SambaGroup::factory()->for($container)->create();
                $groupUser->samba_group_id = $group->id;
            }

            if (! $groupUser->samba_user_id) {
                $container = Container::findOrFail($groupUser->container_id);
                $user = SambaUser::factory()->for($container)->create();
                $groupUser->samba_user_id = $user->id;
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
