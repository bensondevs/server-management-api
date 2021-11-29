<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Order;

use App\Jobs\Order\ProcessOrder;

use App\Repositories\OrderRepository;

class ContainersSeeder extends Seeder
{
    protected $order;

	public function __construct(OrderRepository $orderRepository)
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
        $orders = Order::where('status', 'activated')->get();

        foreach ($orders as $key => $order) {
        	$process = new ProcessOrder($order);
            dispatch($process);
        }
    }
}
