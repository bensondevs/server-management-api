<?php

namespace App\Jobs\Container;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;

use App\Enums\Container\ContainerStatus;

class RetryContainerActivations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $containers = Container::where('status', ContainerStatus::Inactive)->get();
        foreach ($containers as $container) {
            $install = new InstallSystemOnServer($container);
            $container->trackDispatch($install);
        }
    }
}
