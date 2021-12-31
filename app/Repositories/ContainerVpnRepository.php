<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ VpnUser, Container, VpnSubnet };
use App\Enums\Container\Vpn\{ 
	ContainerVpnStatus as Status,
	ContainerVpnEnability as Enability
};
use App\Jobs\Container\Vpn\{
	CreateVpnUser as CreateUser,
	RevokeVpnUser as RevokeUser,

	ReloadVpn as Reload,
	RestartVpn as Restart,
	StopVpn as Stop,
	StartVpn as Start,
	EnableVpn as Enable,
	DisableVpn as Disable,
	ToggleVpnEnability as ToggleEnability,
	
	DownloadVpnConfig as DownloadConfig,
	ChangeVpnSubnet as ChangeSubnet,
	CompleteVpnCheck as CompleteCheck,
	ChangeVpnUserSubnetIp as ChangeUserSubnetIp
};

use App\Http\Resources\{ VpnUserResource, VpnSubnetResource };

class ContainerVpnRepository extends BaseRepository
{
	/**
	 * Repository class constructor function
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Container);
	}

	/**
	 * Populate Existing VPN Users
	 * 
	 * @return array 
	 */
	public function users()
	{
		$container = $this->getModel();
		$vpnUsers = $container->vpnUsers;
		return VpnUserResource::collection($vpnUsers);
	}

	/**
	 * Create new VPN User
	 * 
	 * @param array  $vpnUserData
	 * @return bool
	 */
	public function createUser(array $vpnUserData)
	{
		try {
			$container = $this->getModel();
			$job = new CreateUser($container, $vpnUserData);
			$container->trackDispatch($job);

			$this->setSuccess('Creating VPN user...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed creating VPN user.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Revoke VPN User
	 * 
	 * @param \App\Models\VpnUser $vpnUser
	 * @return bool
	 */
	public function revokeUser(VpnUser $vpnUser)
	{
		try {
			$container = $this->getModel();
			$job = new RevokeUser($vpnUser);
			$container->trackDispatch($job);

			$this->setSuccess('Revoking VPN user...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request for revoking vpn user.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Send command to consumer to start VPN Service
	 * 
	 * @return int
	 */
	public function start()
	{
		try {
			$container = $this->getModel();
			$job = new Start($container);
			$container->trackDispatch($job);

			$this->setSuccess('Successfully send request for starting vpn. Please wait for status change.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request for starting vpn.', $error);
		}

		return $container->current_vpn_status;
	}

	/**
	 * Send command to consumer to reload VPN Service
	 * 
	 * @return int
	 */
	public function reload()
	{
		try {
			$container = $this->getModel();
			$job = new Reload($container);
			$container->trackDispatch($job);

			$this->setSuccess('Successfully send request for reloading vpn. Please wait for the status change.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request for reloading vpn.', $error);
		}

		return $container->current_vpn_status;
	}

	/**
	 * Send restart command to consumer to restart VPN Service
	 * 
	 * @return int
	 */
	public function restart()
	{
		try {
			$container = $this->getModel();
			$job = new Restart($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Successfully send request for restartig vpn. Please wait for the status change');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request for restarting vpn.');
		}

		return $container->current_vpn_status;
	}

	/**
	 * Send stop command to consumer to stop VPN Service
	 * 
	 * @return int
	 */
	public function stop()
	{
		try {
			$container = $this->getModel();
			$job = new Stop($container);
			$container->trackDispatch($job);
			
			$this->setSuccess('Successfully send request for stopping vpn. Please wait for status change.');			
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request for stopping vpn.', $error);
		}

		return $container->current_vpn_status;
	}

	/**
	 * Send complete check vpn command to consumer
	 * 
	 * @return array
	 */
	public function completeCheck()
	{
		try {
			$container = $this->getModel();
			$check = new CompleteCheck($container);
			$container->trackDispatch($check);

			$this->setSuccess('Checking whole VPN...');
		} catch (QueryException $qe) {
			$error = $e->getMessage();
			$this->setError('Failed to do complete checking.', $error);
		}

		return [
			'vpn_status' => $container->current_vpn_status,
			'vpn_enability' => $container->current_vpn_enability,
			'vpn_pid_numbers' => $container->current_vpn_pid_numbers,
		];
	}

	/**
	 * Enable the Container VPN Service
	 * 
	 * @return int
	 */
	public function enable()
	{
		try {
			$container = $this->getModel();

			$enable = new Enable($container);
			$container->trackDispatch($enable);

			$this->setSuccess('Enabling VPN...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request to enable VPN start on boot.', $error);
		}

		return (int) $container->vpn_enability;
	}

	/**
	 * Disable the Container VPN Service
	 * 
	 * @return int
	 */
	public function disable()
	{
		try {
			$container = $this->getModel();

			$disable = new Disable($container);
			$container->trackDispatch($disable);

			$this->setSuccess('Disabling VPN...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request to enable VPN start on boot.', $error);
		}

		return (int) $container->vpn_enability;
	}

	/**
	 * Toggle Enability of Container VPN Service
	 * 
	 * @return int
	 */
	public function toggleEnability()
	{
		try {
			$container = $this->getModel();

			$status = ($container->vpn_enability == Enability::Enabled) ?
				'disable' : 'enable';
			$job = new ToggleEnability($container, $status);
			$container->trackDispatch($job);

			$this->setSuccess('Successfully send request to toggle vpn start on boot.');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send request to toggle vpn start on boot.', $error);
		}

		return (int) (! $container->vpn_enability);
	}

	/**
	 * Change Container VPN Subnet
	 * 
	 * @param string  $subnet
	 * @return bool
	 */
	public function changeSubnet(string $subnet)
	{
		try {
			$container = $this->getModel();
			$change = new ChangeVpnSubnet($container, $subnet);
			$container->trackDispatch($change);

			$this->setSuccess('Changing VPN subnet...');
		} catch (Exception $e) {
			$message = $e->getMessage();
			$this->setError('Failed to change subnet', $message);
			throw new Exception($message);
		}

		return $this->returnResponse();
	}

	/**
	 * Get VPN User Config
	 * 
	 * @param \App\Models\VpnUser  $vpnUser
	 * @return string
	 */
	public function userConfig(VpnUser $vpnUser)
	{
		$container = $this->getModel();
		$job = new DownloadConfig($vpnUser);
		$container->trackDispatch($job);

		return $vpnUser->config_content;
	}

	/**
	 * Change User Subnet IP
	 * 
	 * @param \App\Models\VpnUser  $vpnUser
	 * @param string  $subnetIp
	 * @return bool
	 */
	public function changeUserSubnetIp(VpnUser $vpnUser, string $subnetIp)
	{
		try {
			$container = $this->getModel();
			$job = new changeUserSubnetIp($vpnUser, $subnetIp);
			$container->trackDispatch($job);

			$this->setSuccess('Changing user subnet ip...');
		} catch (Exception $e) {
			$error = $e->getMessage();
			$this->setError('Failed to send command.', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Download VPN User config as zip
	 * 
	 * @param \App\Models\VpnUser  $vpnUser
	 * @return array
	 */
	public function downloadUserConfig(VpnUser $vpnUser)
	{
		// Prepare needed data
		$username = $vpnUser->username;
		$container = $vpnUser->container;

		// Send Command to server to sync database
		$container = $this->getModel();
		$job = new DownloadConfig($container, $username);
		$container->trackDispatch($job);

		// Prepare filename and content
		$filename = $container->id . '_' .  $username . '.ovpn';
		$content = $vpnUser->decoded_config_content;

		// Create zip
        $zipFileName = $filename . '.zip';
        $zipFolder = storage_path('app/public/configzips');
        $zipDestination = $zipFolder . '/' . $zipFileName;
        $zip = new \ZipArchive();
        $zip->open($zipDestination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString($filename, $content);
        $zip->close();

        return [
        	'destination' => $zipDestination, 
        	'filename' => $zipFileName
        ];
	}

	/**
	 * Get list of VPN Subnet IP(s)
	 * 
	 * @return array
	 */
	public function subnetIps()
	{
		$container = $this->getModel();
		$vpnSubnets = VpnSubnet::existsIn($container)->with('vpnUsers')->get();
		return VpnSubnetResource::collection($vpnSubnets);
	}
}
