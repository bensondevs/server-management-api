<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ServiceAddon;

use App\Repositories\ServiceAddonRepository as AddonRepository;

class ServiceAddonsSeeder extends Seeder
{
	protected $addon;

	public function __construct(AddonRepository $addonRepository)
	{
		$this->addon = $addonRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$types = [
    		'space', 'speed', 'duration',
    	];

        for ($index = 1; $index <= 10; $index++) {
        	$this->addon->save([
        		'addon_name' => 'Addon ' . $index,
        		'addon_type' => $types[rand(0, (count($types) - 1))],
        		'quantity' => rand(1, 10),
        		'unit' => 'pcs',
        		'description' => 'Just another random addon.',
        	]);
        	$this->addon->setModel(new ServiceAddon);
        }
    }
}
