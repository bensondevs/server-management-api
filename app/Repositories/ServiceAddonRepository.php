<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\ServiceAddon;

class ServiceAddonRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new ServiceAddon);
	}

	public function find($id)
	{
		$addon = $this->getModel()->find($id);
		$this->setModel($addon);

		return $this->getModel();
	}

	public function save(array $addonData)
	{
		try {
			$addon = $this->getModel();
			$addon->fill($addonData);
			$addon->save();

			$this->setModel($addon);

			$this->setSuccess('Successfully save addon data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save addon data.', $error);
		}

		return $this->getModel();
	}

	public function delete()
	{
		try {
			$addon = $this->getModel();
			$addon->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete addon.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delet addon.', $error);
		}

		return $this->returnResponse();
	}
}
