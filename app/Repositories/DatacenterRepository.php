<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Datacenter;

class DatacenterRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Datacenter);
	}

	/**
	 * Save data to current datacenter model
	 * 
	 * @param array  $datacenterData
	 * @return \App\Models\Datacenter
	 */
	public function save(array $datacenterData)
	{
		try {
			$datacenter = $this->getModel();
			$datacenter->fill($datacenterData);
			$datacenter->client_datacenter_name = $datacenterData['client_datacenter_name'] ? 
				$datacenterData['client_datacenter_name'] :
				$datacenter->datacenter_name;
			$datacenter->save();

			$this->setModel($datacenter);

			$this->setSuccess('Successfully save Data Center data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save Data Center data', $error);
		}

		return $this->getModel();
	}

	/**
	 * Switch status of datacenter
	 * 
	 * @return \App\Models\Datacenter
	 */
	public function switchStatus()
	{
		try {
			$datacenter = $this->getModel();
			$datacenter->switchStatus();

			$this->setModel($datacenter);

			$this->setSuccess('Successfully switch datacenter status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to switch datacenter status.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete datacenter
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$datacenter = $this->getModel();
			$datacenter->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete Data Center.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delet Data Center', $error);
		}

		return $this->returnResponse();
	}
}
