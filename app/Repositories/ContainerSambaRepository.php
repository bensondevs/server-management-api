<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ 
	Container, 
	SambaUser, 
	SambaGroup, 
	SambaShare, 
	SambaDirectory,
	SambaGroupUser 
};
use App\Enums\Container\Samba\{
	ContainerSambaNmbdStatus as NmbdStatus,
	ContainerSambaSmbdStatus as SmbdStatus,
	ContainerSambaNmbdEnability as NmbdEnability,
	ContainerSambaSmbdEnability as SmbdEnability
};
use App\Traits\Repositories\SambaShare as SambaShareTrait;
use App\Jobs\Container\Samba\{
	Group\CreateSambaGroup as CreateGroup,
	Group\DeleteSambaGroup as DeleteGroup,
	Group\User\AddSambaGroupUser as AddGroupUser,
	Group\User\RemoveSambaGroupUser as RemoveGroupUser,

	User\CreateSambaUser as CreateUser,
	User\DeleteSambaUser as DeleteUser,
	User\ChangeSambaUserPassword as ChangeUserPassword,

	CompleteSambaCheck as CompleteCheck,
	CheckSambaStatus as CheckStatus,
	CheckSambaPidNumbers as CheckPidNumbers,
	StartSamba as Start,
	StopSamba as Stop,
	ReloadSamba as Reload,
	RestartSamba as Restart,
	EnableSamba as Enable,
	DisableSamba as Disable,
	ToggleSambaStartOnBoot as ToggleStartOnBoot,
	ToggleSambaBindToPublicIp as ToggleBindToPublicIp,
	ModifySambaSubnetBind as ModifySubnetBind
};

use App\Http\Resources\{ 
	SambaUserResource,
	SambaGroupResource,
	SambaShareResource 
};

class ContainerSambaRepository extends BaseRepository
{
	use SambaShareTrait;

	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Container);
	}

	/**
	 * Do complete check on samba service
	 * 
	 * @return array
	 */
	public function completeCheck()
	{
		$container = $this->getModel();
		$job = new CompleteCheck($container);
		$container->trackDispatch($job);

		$this->setSuccess('Checking samba...');

		$container->load([
			'sambaGroups.users', 
			'sambaUsers', 
			'sambaShares'
		]);

		$sambaGroups = $container->sambaGroups;
		$sambaUsers = $container->sambaUsers;
		$sambaShares = $container->sambaShares;
		$sambaDirectories = $container->sambaDirectories;

		return [
			'samba_status' => $container->current_samba_status,
			'samba_enability' => $container->current_samba_enability,
			'samba_pid_numbers' => $container->current_samba_pid_numbers,
			'samba_groups' => SambaGroupResource::collection($sambaGroups),
			'samba_users' => SambaUserResource::collection($sambaUsers),
			'samba_shares' => SambaShareResource::collection($sambaShares),
		];
	}

	/**
	 * Get samba master data in settings
	 * 
	 * @return array
	 */
	public function settings()
	{
		$container = $this->getModel();

		$directories = $container->sambaDirectories()->withCount(['shares'])->get();
		$groups = $container->sambaGroups()->withCount(['shares', 'users'])->get();
		$users = $container->sambaUsers()->withCount(['shares', 'groups'])->get();
		$shares = $container->sambaShares()->get();

		return [
			'samba_directories' => SambaDirectoryResource::collection($directories),
			'samba_groups' => SambaGroupResource::collection($groups),
			'samba_users' => SambaUserResource::collection($users),
			'samba_shares' => SambaShareResource::collection($shares),
		];
	}

	/**
	 * Send command to start samba service 
	 * and get current status
	 * 
	 * @return int
	 */
	public function start()
	{
		try {
			$container = $this->getModel();
			$job = new Start($container);
			$container->trackDispatch($job);

			$container->setSambaStatusRequesting();

			$this->setSuccess('Samba is now starting.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Samba is faled to start.', $error);
		}

		return $container->current_samba_status;
	}

	/**
	 * Send command to stop samba service 
	 * and get current status
	 * 
	 * @return int
	 */
	public function stop()
	{
		try {
			$container = $this->getModel();
			$job = new Stop($container);
			$container->trackDispatch($job);

			$container->setSambaStatusRequesting();
			
			$this->setSuccess('Stopping samba...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed stopping samba.', $error);
		}

		return $container->current_samba_status;
	}

	/**
	 * Send command to reload samba service 
	 * and get current status
	 * 
	 * @return int
	 */
	public function reload()
	{
		try {
			$container = $this->getModel();
			$job = new Reload($container);
			$container->trackDispatch($job);

			$container->setSambaStatusRequesting();

			$this->setSuccess('Reloading samba...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed reloading samba.', $error);
		}

		return $container->current_samba_status;
	}

	/**
	 * Send command to restart samba service 
	 * and get current status
	 * 
	 * @return int
	 */
	public function restart()
	{
		try {
			$container = $this->getModel();
			$job = new Restart($container);
			$container->trackDispatch($job);

			$container->setSambaStatusRequesting();
			
			$this->setSuccess('Restarting samba...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed restarting samba.', $error);
		}

		return $container->current_samba_status;
	}

	/**
	 * Send command to enable samba service on boot 
	 * and get current enability
	 * 
	 * @return array
	 */
	public function enable()
	{
		try {
			$container = $this->getModel();
			$job = new Enable($container);
			$container->trackDispatch($job);

			$container->setSambaEnabilityRequesting();

			$this->setSuccess('Enabling samba...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed enabling samba.', $error);
		}

		return $container->current_samba_enability;
	}

	/**
	 * Send command to disable samba service on boot 
	 * and get current enability
	 * 
	 * @return int
	 */
	public function disable()
	{
		try {
			$container = $this->getModel();
			$job = new Disable($container);
			$container->trackDispatch($job);

			$container->setSambaEnabilityRequesting();

			$this->setSuccess('Disabling samba...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed enabling samba.', $error);
		}

		return $container->current_samba_enability;
	}

	/**
	 * Send command to enable/disable samba service on boot
	 * and get current enability
	 * 
	 * @return int
	 */
	public function toggleEnability(string $status = 'enable')
	{
		try {
			$container = $this->getModel();
			$job = new ToggleEnability($container, $status);
			$container->trackDispatch($job);
			
			$this->setSuccess('Successfully toggle samba on boot.');			
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to toggle samba on boot.', $error);
		}

		return $container->current_samba_enability;
	}

	/**
	 * Send command to create samba user by supplied data of [username, password]
	 * 
	 * @param  array  $userData
	 * @return bool
	 */
	public function createUser(array $userData)
	{
		try {
			$container = $this->getModel();
			$job = new CreateUser($container, $userData);
			$container->trackDispatch($job);
			
			$this->setSuccess('Creating samba user...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed creating samba user.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to change samba user password
	 * 
	 * @param  \App\Models\SambaUser  $user
	 * @param  string  $password
	 * @return bool
	 */
	public function changeUserPassword(SambaUser $user, string $password)
	{
		try {
			$container = $user->container;
			$job = new ChangeUserPassword($user, $password);
			$container->trackDispatch($job);

			$this->setSuccess('Changing user password');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to change user password.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to delete samba user
	 * 
	 * @param   \App\Models\SambaUser  $user
	 * @return  bool
	 */
	public function deleteUser(SambaUser $user)
	{
		try {
			$container = $user->container;
			$job = new DeleteUser($user);
			$container->trackDispatch($job);
			
			$this->setSuccess('Deleting user...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed deleting user.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to add user to group
	 * 
	 * @param  \App\Models\SambaGroup  $sambaGroup
	 * @param  \App\Models\SambaUser  $sambaUser
	 * @return bool
	 */
	public function addGroupUser(SambaGroup $sambaGroup, SambaUser $sambaUser)
	{
		try {
			$container = $sambaGroup->container ?: $this->getModel();
			$job = new AddGroupUser($sambaGroup, $sambaUser);
			$container->trackDispatch($job);

			$this->setSuccess('Adding samba user to group...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed adding user to samba group.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to remove user from group
	 * 
	 * @param  \App\Models\SambaGroupUser  $groupUser
	 * @return bool
	 */
	public function removeGroupUser(SambaGroupUser $groupUser)
	{
		try {
			$container = $groupUser->container;
			$job = new RemoveGroupUser($groupUser);
			$container->trackDispatch($job);

			$this->setSuccess('Removing samba user from group...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed removing samba user from group.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to toggle bind to public ip
	 * 
	 * @param  string  $status
	 * @return bool
	 */
	public function toggleBindToPublicIp(string $status = 'bind')
	{
		if ($status != 'bind' || $status != 'unbind') {
			$status = 'bind';
		}

		try {
			$container = $this->getModel();
			$job = new ToggleBindToPublicIp($container, $status);
			$container->trackDispatch($job);
			
			$this->setSuccess('Toggling bind to public IP...');			
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed toggling bind to public IP.', $error);
		}

		return $this->returnResponse();
	}

	public function modifySubnetBind(string $bindAddress)
	{
		try {
			$container = $this->getModel();
			$job = new ModifySubnetBind($container, $bindAddress);
			$container->trackDispatch($job);
			
			$this->setSuccess('Modifying subnet bind...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed modifying subnet bind.');
		}

		return $this->returnResponse();
	}
}
