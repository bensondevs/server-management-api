<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use IPTools\Network;
use App\Enums\Subnet\SubnetStatus;
use App\Models\{ User, Subnet, SubnetIp, Datacenter };

class SubnetRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Subnet);
	}

	/**
	 * Save subnet for creation or updating
	 * 
	 * @param array  $subnetData
	 * @return \App\Models\Subnet
	 */
	public function save(array $subnetData)
	{
		try {
			$subnet = $this->getModel();
			$subnet->fill($subnetData);
			$subnet->save();

			$this->setModel($subnet);

			$this->setSuccess('Successfully save subnet data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save subnet data', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set subnet status
	 * 
	 * @param int $status
	 * @return \App\Models\PaymentMethod
	 */
	public function setStatus(int $status = 0)
	{
		try {
			$subnet = $this->getModel();
			$subnet->status = $status;
			$subnet->save();

			$this->setModel($subnet);

			$this->setSuccess('Successfully set subnet status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set subnet status.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete subnet from the server
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$subnet = $this->getModel();
			$subnet->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete subnet data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete subnet data.', $error);
		}

		return $this->returnResponse();
	}
}
