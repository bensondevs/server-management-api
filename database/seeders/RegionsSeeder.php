<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Repositories\RegionRepository;

class RegionsSeeder extends Seeder
{
	protected $region;

	public function __construct(
		RegionRepository $regionRepository
	)
	{
		$this->region = $regionRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->region->save(['region_name' => 'Europe']);
        /*$this->region->setModel(new \App\Models\Region());
        $this->region->save(['region_name' => 'Asia']);*/
    }
}
