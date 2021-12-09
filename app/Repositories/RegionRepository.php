<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Region;

class RegionRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @param void
	 */
	public function __construct()
	{
		$this->setInitModel(new Region);
	}

	/**
	 * Save region data
	 * 
	 * @param  array  $regionData
	 * @return \App\Models\Region
	 */
	public function save(array $regionData)
	{
		try {
			$region = $this->getModel();
			$region->fill($regionData);
			$region->save();

			$this->setModel($region);

			$this->setSuccess('Successfully save region data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save region data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete region
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$region = $this->getModel();
			$region->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete region.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete region.', $error);
		}

		return $this->returnResponse();
	}
}
