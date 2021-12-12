<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{ Datacenter, Subnet };

class SubnetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$datacenter = Datacenter::first();
        $subnet = Subnet::create([
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
    }
}
