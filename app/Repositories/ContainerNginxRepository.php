<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;

use App\Traits\Repositories\NginxLocation;

use App\Jobs\Container\Nginx\{
	CompleteNginxCheck as CompleteCheck,
	StartNginx as Start,
	ReloadNginx as Reload,
	RestartNginx as Restart,
	StopNginx as Stop,
	EnableNginx as Enable,
	DisableNginx as Disable
};
use App\Jobs\Container\Nginx\Location\{
	CreateNginxLocation as CreateLocation,
	RemoveNginxLocation as RemoveLocation
};

use App\Http\Resources\NginxLocationResource;
use App\Models\Container;

class ContainerNginxRepository extends AmqpRepository
{
	use NginxLocation;

	/**
	 * Repository constructor class
	 */
	public function __construct()
	{
		$this->setInitModel(new Container);
	}

	/**
	 * Send command to do complete check of NGINX
	 * 
	 * @return array
	 */
	public function completeCheck()
	{
		try {
			$container = $this->getModel();
			$job = new CompleteCheck($container);
			$container->trackDispatch($job);

			$this->setSuccess('Successfully do complete NGINX check.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to do complete NGINX check.', $error);
		}

		return [
			'nginx_status' => $container->current_nginx_status,
			'nginx_enability' => $container->current_nginx_enability,
			'nginx_pid_numbers' => $container->current_nginx_pid_numbers,
			'nginx_locations' => $this->locations(),
		];
	}

	/**
	 * Send command to start NGINX Service
	 * 
	 * @return int
	 */
	public function start()
	{
		try {
			$container = $this->getModel();
			$job = new Start($container);
			$container->trackDispatch($job);

			$this->setSuccess('Starting NGINX...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to starting NGINX.', $error);
		}

		return $container->current_nginx_status;
	}

	/**
	 * Send command to restart NGINX Service
	 * 
	 * @return int
	 */
	public function restart()
	{
		try {
			$container = $this->getModel();
			$job = new Restart($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Restarting NGINX...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to restart NGINX.', $error);
		}

		return $container->current_nginx_status;
	}

	/**
	 * Send command to reload NGINX Service
	 * 
	 * @return int
	 */
	public function reload()
	{
		try {
			$container = $this->getModel();
			$job = new Reload($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Reloading NGINX...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to reloading NGINX.', $error);
		}

		return $container->current_nginx_status;
	}

	/**
	 * Send command to stop NGINX Service
	 * 
	 * @return int
	 */
	public function stop()
	{
		try {
			$container = $this->getModel();
			$job = new Stop($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Stopping NGINX...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to stop NGINX.', $error);
		}

		return $container->current_nginx_status;
	}

	/**
	 * Send command to enable NGINX Service start on boot
	 * 
	 * @return int
	 */
	public function enable()
	{
		try {
			$container = $this->getModel();
			$job = new Enable($container);
			$container->trackDispatch($job);

			$this->setSuccess('Enabling NGINX...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send command to stop NGINX.', $error);
		}

		return $container->current_nginx_enability;
	}

	/**
	 * Send command to disable NGINX Service start on boot
	 * 
	 * @return int
	 */
	public function disable()
	{
		try {
			$container = $this->getModel();
			$job = new Disable($container);
			$container->trackDispatch($job);

			$this->setSuccess('Disabling NGINX...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send command to disable NGINX.', $error);
		}

		return $container->current_nginx_enability;
	}
}
