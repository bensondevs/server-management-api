<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Subnet, SubnetIp };

class SubnetIpRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new SubnetIp);
	}

	/**
	 * Assign subnet ip to a user
	 * 
	 * @param \App\Models\User  $user
	 * @return \App\Models\SubnetIp
	 */
	public function assignToUser(User $user)
	{
		try {
			$ip = $this->getModel();
			$ip->assignTo($user);
			$this->setModel($ip);

			$this->setSuccess('Successfully assign IP to a user.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to assign IP to a user.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Switch subnet ip to forbidden or otherwise
	 * 
	 * @return \App\Models\SubnetIp
	 */
	public function switchForbidden()
	{
		try {
			$ip = $this->getModel();
			$ip->switchForbidden();

			$this->setModel($ip);

			$this->setSuccess('Successfully switch Subnet IP forbidden status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to switch Subnet IP forbidden status.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Save subnet ip
	 * 
	 * @param  array  $ipData
	 * @return \App\Models\SubnetIp
	 */
	public function save(array $ipData)
	{
		try {
			$ip = $this->getModel();
			$ip->fill($ipData);
			$ip->ip_address = $ipData['ip_address'];
			$ip->save();

			$this->setModel($ip);

			$this->setSuccess('Successfully save Subnet IP data.');
		} catch (QueryException $e) {
			$error = $qe->getMessage();
			$this->setError('Failed to save Subnet IP data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete subnet ip
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$ip = $this->getModel();
			$ip->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete Subnet IP data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete Subnet IP data.', $error);
		}

		return $this->returnResponse();
	}
}
