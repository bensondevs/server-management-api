<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Repositories\AmqpRepository;

use App\Traits\TrackExecution;

use App\Models\Container;
use App\Models\ServicePlan;

use App\Enums\Container\ContainerStatus;

class CreateContainerOnServer implements ShouldQueue
{
    use TrackExecution;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

    private $serverContainer;
    private $servicePlan;

    private $amqpRepo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Container $serverContainer, $servicePlanId = '')
    {
        $this->serverContainer = $serverContainer;

        if ($servicePlanId) {
            $this->servicePlan = ServicePlan::findOrFail($servicePlanId);
        } else {
            $order = $serverContainer->order;
            $this->servicePlan = $order->plan;
        }

        $this->amqpRepo = new AmqpRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $container = $this->serverContainer;
        $servicePlan = $this->servicePlan;
        $server = $container->server;
        $subnetIp = $container->subnetIp;

        // Check container in local
        $container = $container->fresh();
        if ($container->created_on_server_at) {
            $this->finish();
        }

        $requestId = generateUuid();

        $this->amqpRepo->connectServerQueue($server);
        $this->amqpRepo->publishJson([
            'uuid' => $requestId,
            'command' => 'create container',
            'container_id' => $container->id,
            'plan_name' => $servicePlan->plan_code,
            'disk_size' => $container->disk_size,
            'ip_address' => $subnetIp->ip_address,
        ]);
        $response = $this->amqpRepo->consumeServerResponse($server, $requestId);

        if (isset($response['status'])) {
            // Assign subnet IP to certain user
            $subnetIp->assigned_user_id = $container->customer_id;
            $subnetIp->save();

            // Set container create on server
            $container->created_on_server_at = now();
            $container->status = ContainerStatus::Inactive;
            $container->save();

            // Install System
            $installSystem = new InstallSystemOnServer($container);
            $installSystem->delay(1);
            $container->trackDispatch($installSystem);
        }

        $this->recordResponse($response);
    }
}
