<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ NfsFolder, Container };

class NfsFolderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NfsFolder::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (NfsFolder $folder) {
            if (! $folder->container_id) {
                $container = Container::first() ?: Container::factory()->create();
                $folder->container_id = $container->id;
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
            'folder_name' => random_string(7),
        ];
    }
}
