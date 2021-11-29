<?php

namespace App\Jobs\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Order;
use App\Models\OrderPlan;
use App\Models\Datacenter;
use App\Models\WaitingContainer;

use App\Traits\TrackExecution;

use App\Repositories\ServerRepository;
use App\Repositories\SubnetRepository;
use App\Repositories\SubnetIpRepository;
use App\Repositories\ContainerRepository;

class ProcessOrder implements ShouldQueue
{
    use TrackExecution;
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    private $serverRepo;
    private $subnetRepo;
    private $subnetIpRepo;
    private $containerRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

        $this->serverRepo = new ServerRepository();
        $this->subnetRepo = new SubnetRepository();
        $this->subnetIpRepo = new SubnetIpRepository();
        $this->containerRepo = new ContainerRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = $this->order;
        $order->load(['plan']);

        $metaContainer = $order->meta_container;
        if (! $orderPlan = $order->plan) {
            return;
        }
        $servicePlan = $orderPlan->servicePlan;
        $datacenter = Datacenter::findOrFail($metaContainer['datacenter_id']);

        if (! $server = $this->serverRepo->leastSelectedOf($datacenter)) {
            return $waitingContainer = WaitingContainer::create([
                'order_id' => $order->id,
                'duration_days' => $order->plan->duration_days,
                'waiting_since' => carbon()->now(),
            ]);
        }

        if (! $subnet = $this->subnetRepo->leastSelectedOf($datacenter)) {
            return $waitingContainer = WaitingContainer::create([
                'order_id' => $order->id,
                'duration_days' => $order->plan->duration_days,
                'waiting_since' => carbon()->now(),
            ]);
        }

        if (! $subnetIp = $this->subnetIpRepo->selectRandomFreeIp($subnet)) {
            return $waitingContainer = WaitingContainer::create([
                'order_id' => $order->id,
                'duration_days' => $order->plan->duration_days,
                'waiting_since' => carbon()->now(),
            ]);
        }

        $container = $this->containerRepo->save([
            'order_id' => $order->id,
            'service_plan_id' => $servicePlan->id,
            'customer_id' => $order->customer_id,
            'hostname' => isset($metaContainer['hostname']) ? 
                $metaContainer['hostname'] : 
                null,
            'client_email' => isset($metaContainer['client_email']) ?
                $metaContainer['client_email'] : 
                $order->customer->email,
            'server_id' => $server->id,
            'subnet_id' => $subnet->id,
            'subnet_ip_id' => $subnetIp->id,

            'disk_size' => $metaContainer['disk_size'],

            'order_date' => $order->created_at,
            'activation_date' => carbon()->today(),
            'expiration_date' => carbon()->today()->addDays(
                $orderPlan->duration_days
            ),
        ]);

        if ($this->containerRepo->status !== 'success' || (! $container)) {
            echo $this->containerRepo->queryError;
            echo 'Failed to create container.';
            $this->fail();
        }
    }
}
