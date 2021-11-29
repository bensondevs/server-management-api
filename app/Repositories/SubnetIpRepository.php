<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\User;
use App\Models\Subnet;
use App\Models\SubnetIp;

class SubnetIpRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new SubnetIp);
	}

	public function assignables(array $options, bool $pagination = false)
	{
		$subnetIp = SubnetIp::assignable();
		$this->setModel($subnetIp);

		return $this->all($options, $pagination);
	}

	public function selectRandomFreeIp(Subnet $subnet)
	{
		return SubnetIp::assignable()
			->where('subnet_id', $subnet->id)
			->first();
	}

	public function assignSelectedIpTo(User $user)
	{
		try {
			$ip = $this->getModel();
			$ip->assignTo($user);

			$this->setModel($ip);

			$this->setSuccess('Successfully assign IP to a user.');
		} catch (QueryException $qe) {
			$this->setError('Failed to assign IP to a user.');
		}

		return $this->getModel();
	}

	public function switchForbidden()
	{
		try {
			$ip = $this->getModel();
			$ip->switchForbidden();

			$this->setModel($ip);

			$this->setSuccess('Successfully switch Subnet IP forbidden status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to switch Subnet IP forbidden status', $error);
		}

		return $this->getModel();
	}

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
