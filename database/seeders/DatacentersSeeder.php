<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Region;
use App\Models\Datacenter;
use App\Repositories\DatacenterRepository;

class DatacentersSeeder extends Seeder
{
	protected $datacenter;

	public function __construct(DatacenterRepository $datacenter)
	{
		$this->datacenter = $datacenter;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $region = Region::all()->first();

        $this->datacenter->save([
            'region_id' => $region->id,
        	'datacenter_name' => 'Lithuania',
	        'client_datacenter_name' => 'Lithuania',
	        'location' => 'Lithuania',
        ]);
    }
}
