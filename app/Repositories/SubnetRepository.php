<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use IPTools\Network;

use App\Models\User;
use App\Models\Subnet;
use App\Models\SubnetIp;
use App\Models\Datacenter;

use App\Repositories\Base\BaseRepository;

class SubnetRepository extends BaseRepository
{
	public $ip;

	public function __construct()
	{
		$this->setInitModel(new Subnet);
		$this->ip = new SubnetIp;
	}

	public function active()
	{
		$subnets = Subnet::active()->get();

		$this->setCollection($subnets);

		return $this->getCollection();
	}

	public function allWithFreeIps()
	{
		$subnets = Subnet::with('freeIps')
			->usable()
			->get();

		$this->setCollection($subnets);

		return $this->getCollection();
	}

	public function allWithCount(array $options = [], bool $pagination = false)
	{
		$options['scopes'][] = 'withCountIps';
		return $this->all($options, $pagination);
	}

	public function mostSelectedOf(Datacenter $datacenter)
	{
		$subnet = Subnet::withCount('containers')
			->where('datacenter_id', $datacenter->id)
			->get()
			->sortBy('containers_count')
			->first();

		$this->setModel($subnet);

		return $this->getModel();
	}

	public function leastSelectedOf(Datacenter $datacenter)
	{
		$subnet = Subnet::withCount('containers')
			->where('datacenter_id', $datacenter->id)
			->get()
			->sortByDesc('containers_count')
			->first();

		$this->setModel($subnet);

		return $this->getModel();
	}

	public function find($id)
	{
		$subnet = Subnet::withCount([
			'ips', 
			'ips as available_ips' => function ($ip) {
				$ip->whereNull('assigned_user_id');
			}
		])->findOrFail($id);

		$this->setModel($subnet);
		return $this->getModel();
	}

	public function save($subnetData)
	{
		try {
			$subnet = $this->getModel();
			$subnet->fill($subnetData);
			$subnet->save();

			$this->setModel($subnet);

			$this->setSuccess('Successfully save subnet data.');
		} catch (QueryException $qe) {
			$this->setError('Failed to save subnet data', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function toggleStatus()
	{
		try {
			$subnet = $this->getModel();
			$subnet->status = ($subnet->status == 'active') ?
				'inactive' : 'active';
			$subnet->save();

			$this->setModel($subnet);

			$this->setSuccess('Successfully toggle subnet status.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to toggle subnet status.', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}

	public function selectRandomFreeIp()
	{
		$subnet = $this->getModel();
		$this->ip = SubnetIp::assignable()
			->where('subnet_id', $subnet->id)
			->get()
			->first();

		return $this->ip;
	}

	public function findIp($id)
	{
		return $this->ip = SubnetIp::findOrFail($id);
	}

	public function toggleIpUsable()
	{
		try {
			$ip = $this->ip;
			$ip->is_usable = (! $ip->is_usable);
			$ip->save();

			$this->ip = $ip;

			$this->setSuccess('Successfully toggle IP Address usability.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to toggle IP Address usability', 
				$qe->getMessage()
			);
		}

		return $this->ip;
	}

	public function assignSelectedIpTo(User $user)
	{
		try {
			$this->ip->assignTo($user);

			$this->setSuccess('Successfully assign IP to User');
		} catch (QueryException $qe) {
			$this->setError('Failed to assign IP to User', $qe->getMessage());
		}

		return $this->ip;
	}

	public function delete()
	{
		try {
			$subnet = $this->getModel();
			$subnet->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete subnet data.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to delete subnet data.', 
				$qe->getMessage()
			);
		}

		return $this->returnResponse();
	}
}
