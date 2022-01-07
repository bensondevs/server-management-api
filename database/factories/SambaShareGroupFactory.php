<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ SambaShare, SambaGroup, SambaShareGroup, Container };

class SambaShareGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SambaShareGroup::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (SambaShareGroup $shareGroup) {
            if (! $shareGroup->container_id) {
                $container = Container::first() ?:
                    Container::factory()->create();
                $shareGroup->container_id = $container->id;
            }

            if (! $shareGroup->samba_share_id) {
                $container = Container::findOrFail($shareGroup->container_id);
                $share = SambaShare::factory()->for($container)->create();
                $shareGroup->samba_share_id = $share->id;
            }

            if (! $shareGroup->samba_group_id) {
                $container = Container::findOrFail($shareGroup->container_id);
                $group = SambaGroup::factory()->for($container)->create();
                $shareGroup->samba_group_id = $group->id;
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
