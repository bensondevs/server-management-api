<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

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
	EnableNfs as Enable,
	DisableNfs as Disable,

	BindNfsPublicIp as BindPublicIp,
	UnbindNfsPublicIp as UnbindPublicIp
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
		try {
			$container = $this->getModel();
			$job = new Enable($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Enabling NFS star on boot...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed enabling NFS start on boot', $error);
		}

		return $container->current_nfs_enability;
	}

	/**
	 * Disable NFS Service on Boot
	 * 
	 * @return int
	 */
	public function disable()
	{
		try {
			$container = $this->getModel();
			$job = new Disable($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Enabling NFS start on boot...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed enabling NFS start on boot', $error);
		}

		return $container->current_nfs_enability;
	}

	/**
	 * Bind NFS to public ip
	 * 
	 * @return int
	 */
	public function bindPublicIp()
	{
		try {
			$container = $this->getModel();
			$job = new BindPublicIp($container);
			$container->trackDispatch($job);

			$this->setSuccess('Binding NFS to public IP...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to bind NFS to public IP.', $error);
		}

		return $container->current_nfs_bind_public_ip;
	}

	/**
	 * Unbind NFS to public ip
	 * 
	 * @return int
	 */
	public function unbindPublicIp()
	{
		try {
			$container = $this->getModel();
			$job = new unbindPublicIp($container);
			$container->trackDispatch($job);

			$this->setSuccess('Unbinding NFS from public IP...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to unbind NFS from public IP.', $error);
		}

		return $container->current_nfs_bind_public_ip;
	}
}
