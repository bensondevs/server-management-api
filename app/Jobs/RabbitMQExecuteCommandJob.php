<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;

class RabbitMQExecuteCommandJob extends BaseJob
{
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $commands;

	public function setCommands(array $commands)
	{
		$this->commands = $commands;
	}

    public function fire()
    {
        $payload = $this->commands;

        $class = User::class;
        $method = 'all';

        ($this->instance = $this->resolve($class))->{$method}($this, $payload);
    }
}
