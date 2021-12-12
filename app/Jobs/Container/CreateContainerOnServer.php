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
use App\Models\{ Container, ServicePlan };
use App\Enums\Container\ContainerStatus;
use App\Jobs\Container\ContainerBaseJob;

class CreateContainerOnServer extends ContainerBaseJob implements ShouldQueue
{
    use TrackExecution;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Amount of seconds to wait on execution
     * 
     * @var  int
     */
    public $timeout = 3600;

    /**
     * Container that's creating on server
     * 
     * @var \App\Models\Container|null
     */
    private $serverContainer;

    /**
     * Service plan to be attached to the server
     * 
     * @var \App\Models\ServicePlan|null
     */
    private $servicePlan;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Container  $serverContainer
     * @param  \App\Models\ServicePlan  $plan
     * @return void
     */
    public function __construct(Container $serverContainer, ServicePlan $plan)
    {
        parent::__construct();
        $this->serverContainer = $serverContainer;
        $this->servicePlan = $plan;
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

        $response = $this->sendRequest($server, [
            'command' => 'create container',
            'container_id' => $container->id,
            'plan_name' => $servicePlan->plan_code,
            'disk_size' => $container->disk_size,
            'ip_address' => $subnetIp->ip_address,
        ]);

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
