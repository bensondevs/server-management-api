<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Order;
use App\Models\Datacenter;
use App\Models\ServicePlan;
use App\Models\ServiceAddon;

use App\Repositories\OrderRepository;
use App\Repositories\ContainerRepository;

class OrdersSeeder extends Seeder
{
	protected $order;

	public function __construct(
		OrderRepository $orderRepository
	)
	{
		$this->order = $orderRepository;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servicePlans = ServicePlan::all();
        $serviceAddons = ServiceAddon::all();
        $datacenters = Datacenter::all();

        for ($index = 0; $index < 100; $index++) {
            $servicePlan = $servicePlans->random();

        	$amount = $servicePlan->subscription_fee;
        	$vatPercentage = rand(1, 30);
        	$total = $amount + ($amount * $vatPercentage / 100);

            $metaContainer = [
                'hostname' => 'www.test' . ($index + 1) . '.com', 
                'datacenter_id' => $datacenters->random()->id,
                'disk_size' => 5000,
            ];

            /*$addonList = [];
            for ($count = 0; $count < rand(1, 5); $count++) {
                array_push($addonList, [
                    'service_addon_id' => $serviceAddons->random()->id,
                    'quantity' => rand(1, 10),
                    'note' => 'This is seeder data',
                ]);
            }*/

        	$this->order->save([
                'order_data' => [
                    'status' => rand(0, 4),
                    'customer_id' => User::inRandomOrder()->first()->id,
                    'vat_size_percentage' => $vatPercentage,
                    'meta_container' => $metaContainer,
                ],
                'plan_data' => [
            		'service_plan_id' => $servicePlan->id,
                    'quantity' => rand(1, 10),
                    'note' => 'This is seeder created order',
                ],
                // 'addons_list' => $addonList,
        	]);
            if ($queryError = $this->order->queryError) {
                dd($queryError);
            }
            $this->order->setModel(new Order);
        }
    }
}
