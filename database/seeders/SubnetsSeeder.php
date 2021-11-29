<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Datacenter;
use App\Models\Subnet;
use App\Repositories\SubnetRepository;

class SubnetsSeeder extends Seeder
{
	protected $subnet;

	public function __construct(SubnetRepository $subnetRepository)
	{
		$this->subnet = $subnetRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$datacenter = Datacenter::all()->first();

        $subnet = $this->subnet->save([
        	'datacenter_id' => $datacenter->id,
        	'subnet_mask' => '185.111.182.1/24',
        ]);
        foreach ($subnet->ips as $ip) {
            $ipAddress = $ip->ip_address;
            $lastDigit = str_replace('185.111.182.', '', $ipAddress);

            if ($lastDigit < 20 OR $lastDigit > 30) {
                $ip->setForbidden();
            }
        }

        $this->subnet->setModel(new Subnet);
        $this->subnet->save([
        	'datacenter_id' => $datacenter->id,
        	'subnet_mask' => '127.0.0.1/24',
        ]);
    }
}
