<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\{ 
	Container, 
	SambaUser, 
	SambaGroup, 
	SambaShare, 
	SambaDirectory,
	SambaGroupUser 
};

use App\Traits\Repositories\SambaShare as SambaShareTrait;

use App\Jobs\Container\Samba\{
	Directory\CreateSambaDirectory as CreateDirectory,
	Directory\DeleteSambaDirectory as DeleteDirectory,

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
	ToggleSambaStartOnBoot as ToggleStartOnBoot,
	ToggleSambaBindToPublicIp as ToggleBindToPublicIp,
	ModifySambaSubnetBind as ModifySubnetBind
};

use App\Http\Resources\{ 
	SambaUserResource,
	SambaGroupResource, 
	SambaDirectoryResource 
};

class ContainerSambaRepository extends AmqpRepository
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
			'sambaShares', 
			'sambaDirectories'
		]);

		$sambaGroups = $container->sambaGroups;
		$sambaUsers = $container->sambaUsers;
		$sambaShares = $container->sambaShares;
		$sambaDirectories = $container->sambaDirectories;

		return [
			'samba_status' => $container->current_samba_status,
			'samba_enability' => $container->current_samba_enability,
			'samba_pid_numbers' => $container->current_samba_pid_numbers,
			'samba_directories' => SambaDirectoryResource::collection($sambaDirectories),
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
	 * @return int
	 */
	public function enable()
	{
		//
	}

	/**
	 * Send command to disable samba service on boot 
	 * and get current enability
	 * 
	 * @return int
	 */
	public function disable()
	{
		//
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
	}

	public function users()
	{
		$container = $this->getModel();
		$users = $container->sambaUsers;
		return SambaUserResource::collection($users);
	}

	public function notInGroupUsers(SambaGroup $group)
	{
		$users = SambaUser::where('container_id', $group->container_id)
			->whereHas('groups', function ($query) use ($group) {
				$query->where('samba_groups.id', '!=', $group->id);
			})->get();

		return SambaUserResource::collection($users);
	}

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

	public function addShareUser(SambaShare $share, SambaUser $user)
	{
		try {
			$container = $share->container;
			$job = new AddShareUser($share, $user);
			$container->trackDispatch($job);
			
			$this->setSuccess('Adding user to share...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed adding share user.', $error);
		}

		return $this->returnResponse();
	}

	public function removeShareUser(SambaShareUser $shareUser)
	{
		try {
			$container = $shareUser->container;
			$job = new RemoveShareUser($shareUser);
			$container->trackDispatch($job);
			
			$this->setSuccess('Removing share user...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed removing share user.', $error);
		}

		return $this->returnResponse();
	}

	public function addShareGroup(SambaShare $share, SambaGroup $group)
	{
		try {
			$container = $share->container;
			$job = new AddShareGroup($share, $group);
			$container->trackDispatch($job);
			
			$this->setSuccess('Adding group to share...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed adding group to share.', $error);
		}

		return $this->returnResponse();
	}

	public function removeShareGroup(SambaShareGroup $shareGroup)
	{
		try {
			$container = $shareGroup->container;
			$job = new RemoveShareGroup($shareGroup);
			$container->trackDispatch($job);
			
			$this->setSuccess('Removing group from share...');		
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed removing group from share.', $error);
		}

		return $this->returnResponse();
	}

	public function addGroupUser(SambaGroup $sambaGroup, SambaUser $sambaUser)
	{
		try {
			$container = $sambaGroup->container;
			$job = new AddGroupUser($sambaGroup, $sambaUser);
			$container->trackDispatch($job);

			$this->setSuccess('Adding samba user to group...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed adding user to samba group.', $error);
		}

		return $this->returnResponse();
	}

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
