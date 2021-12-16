<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;
use \ZipArchive;

use App\Consumers\JsonConsumer;

use App\Models\{
	User,
	Order,
	VpnUser,
	SubnetIp,
	Container,
	Datacenter,
	JobTracker,
	ServicePlan,
	WaitingContainer
};

use App\Enums\Container\ContainerStatus;

use App\Jobs\Order\ProcessOrder;
use App\Jobs\Container\{
	DestroyContainer,
	CreateContainerOnServer,
	InstallSystemOnServer
};

class ContainerRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel((new Container));
	}

	/**
	 * Save container data in local database
	 * 
	 * @param array  $containerData
	 * @return \App\Models\Container
	 */
	public function save(array $containerData)
	{
		try {
			$container = $this->getModel();
			$container->fill($containerData);
			$container->status = isset($container->status) ?
				$container->status : ContainerStatus::Inactive;

			$ip = SubnetIp::find($container->subnet_ip_id); 
			$container->id = $container->generate_id($ip->ip_address);
			$container->save();

			$this->setModel($container);

			$this->setSuccess('Successfully save container data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save container data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Get current container of the user
	 * 
	 * @param \App\Models\User  $user
	 * @return \App\Models\Container|null
	 */
	public function current(User $user)
	{
		$container = Container::ownedBy($user);
		if ($currentContainer = $container->current()->first()) {
			return $currentContainer;
		}

		if ($activeContainer = $container->active()->first()) {
			$activeContainer->setCurrent();
			return $activeContainer;
		}

		return null;
	}

	/**
	 * Populate command executions
	 * 
	 * @param \DateTime|null  $from
	 * @param \DateTime|null  $till
	 * @return Illuminate\Support\Collection
	 */
	public function commandExecutions($from = null, $till = null)
	{
		$container = $this->getModel();
		return JobTracker::ofContainer($container)
			->from($from)
			->till($till)
			->orderByDesc('created_at')
			->get();
	}

	/**
	 * Set selected container as current container
	 * 
	 * @return \App\Models\Container
	 */
	public function setCurrent()
	{
		try {
			$container = $this->getModel();
			$container->setCurrent();

			$this->setModel($container);

			$this->setSuccess('Successfully set container as current container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set container as current container.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Check if container is exists in server
	 * 
	 * @return void
	 */
	public function checkExistsInServer()
	{
		try {
			$container = $this->getModel();
			$job = new CheckExistsInServer($container);
			$container->trackDispatch($job);

			$this->setSuccess('Checking container exists in server...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to check container exists in server...');
		}
	}

	/**
	 * Create container on server by rabbitmq consumer
	 * 
	 * @return bool
	 */
	public function createOnServer()
	{
		try {
			$container = $this->getModel();

			$plan = $container->getServicePlan();
			$job = new CreateContainerOnServer($container, $plan);
			$container->trackDispatch($job);
			
			$this->setSuccess('Creating in server...');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create in server.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Install system on created container in server
	 * 
	 * @return bool
	 */
	public function installSystem()
	{
		try {
			$container = $this->getModel();
			$job = new InstallSystemOnServer($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Installing system on container...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to install system on container.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Set container status as activated
	 * The activated container will be ready to be used
	 * 
	 * @return \App\Models\Container
	 */
	public function activate()
	{
		try {
			$container = $this->getModel();
			$container->activate();

			$this->setModel($container);

			$this->setSuccess('Successfully activate container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to activate container.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete container in local database
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$container = $this->getModel();
			$container->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete container.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete container.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Destory container in server through rabbitmq
	 * 
	 * @return bool
	 */
	public function destroyInServer()
	{
		try {
			$container = $this->getModel();

			$job = new DestroyContainer($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Destroying container...');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed destroying container.', $error);
		}

		return $this->returnResponse();
	}
}
