<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Region;

use App\Repositories\Base\BaseRepository;

class RegionRepository extends BaseRepository
{
	public function __construct()
	{
		$region = new Region;
		$this->setInitModel($region);
	}

	public function find($id)
	{
		$region = Region::findOrFail($id);
		$this->setModel($region);

		return $this->getModel();
	}

	public function save($regionData)
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
