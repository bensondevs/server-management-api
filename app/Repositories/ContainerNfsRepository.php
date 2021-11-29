<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Traits\Repositories\{ NfsFolder, NfsExport };

use App\Models\Container;

use App\Enums\Container\ContainerNfsStartOnBootStatus;

use App\Jobs\Container\Nfs\{
	CompleteNfsCheck as CompleteCheck,
	
	RestartNfs as Restart,
	ReloadNfs as Reload,
	StartNfs as Start,
	StopNfs as Stop,
	ToggleNfsStartOnBoot as ToggleStartOnBoot
};

use App\Http\Resources\{
	NfsExportResource, 
	NfsFolderResource
};

class ContainerNfsRepository extends BaseRepository
{
	use NfsFolder, NfsExport;

	/**
	 * Repository constructor function
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Container);
	}

	/**
	 * Do complete check to NFS
	 * 
	 * @return array
	 */
	public function completeCheck()
	{
		try {
			$container = $this->getModel();
			$job = new CompleteCheck($container);
			$container->trackDispatch($job);

			$this->setSuccess('Checking NFS...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to check NFS.');
		}

		$folders = $container->nfsFolders;
		$exports = $container->nfsExports()->with('folder')->get();
		return [
			'nfs_status' => $container->current_nfs_status,
			'nfs_pid_numbers' => $container->current_nfs_pid_numbers,
			'nfs_start_on_boot_status' => $container->current_nfs_start_on_boot_status,
			'folders' => NfsFolderResource::collection($folders),
			'exports' => NfsExportResource::collection($exports),
		];
	}

	/**
	 * Start NFS Service
	 * 
	 * @return int
	 */
	public function start()
	{
		try {
			$container = $this->getModel();
			$job = new Start($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Starting NFS...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed starting NFS', $error);
		}

		return $container->current_nfs_status;
	}

	/**
	 * Restart NFS Service
	 * 
	 * @return int
	 */
	public function restart()
	{

		try {
			$container = $this->getModel();
			$job = new Restart($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Restarting NFS...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed restarting NFS', $error);
		}

		return $container->current_nfs_status;
	}

	/**
	 * Reload NFS Service
	 * 
	 * @return int
	 */
	public function reload()
	{
		try {
			$container = $this->getModel();
			$job = new Reload($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Reloading NFS...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed reloading NFS', $error);
		}

		return $container->current_nfs_status;
	}

	/**
	 * Stop NFS Service
	 * 
	 * @return int
	 */
	public function stop()
	{
		try {
			$container = $this->getModel();
			$job = new Stop($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Stopping NFS...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed stopping NFS', $error);
		}

		return $container->current_nfs_status;
	}

	/**
	 * Enable NFS Service on Boot
	 * 
	 * @return int
	 */
	public function enable()
	{
		//
	}

	/**
	 * Disable NFS Service on Boot
	 * 
	 * @return int
	 */
	public function disable()
	{
		//
	}

	public function toggleStartOnBoot()
	{
		try {
			$container = $this->getModel();
			$status = ($container->nfs_start_on_boot_status == ContainerNfsStartOnBootStatus::Enabled) ? 
				$this->disable() : $this->enable();
			$job = new ToggleStartOnBoot($container, $status);
			$container->trackDispatch($job);

			$this->setSuccess('Toggling start on boot NFS...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed togglong start on boot NFS.', $error);
		}

		return $container->current_nfs_start_on_boot_status;
	}
}
