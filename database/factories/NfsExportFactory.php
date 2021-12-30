<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{ NfsExport, NfsFolder, Container };

class NfsExportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NfsExport::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (NfsExport $export) {
            if (! $export->container_id) {
                $container = Container::first() ?: Container::factory()->create();
                $export->container_id = $container->id;
            }

            if (! $export->nfs_folder_id) {
                $container = Container::findOrFail($export->container_id);
                $folder = NfsFolder::inRandomOrder()
                    ->where('container_id', $container->id)
                    ->first() ?: NfsFolder::factory()->for($container)->create();
                $export->nfs_folder_id = $folder->id;
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
            'ip_address' => mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255) . '.' . mt_rand(0, 255),
            'permissions' => 'rw',
        ];
    }
}
