<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\Activity;
use App\Repositories\Base\BaseRepository;

class ActivityLogRepository extends BaseRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Activity);
	}

	/**
	 * Save {{$variableName}} to database
	 * 
	 * @param array  $data
	 * @return \App\Models\ 
	 */
	public function save(array $data = [])
	{
		try {
			$activityLog = $this->getModel();
			$activityLog->fill($data);
			$activityLog->save();

			$this->setModel($activityLog);

			$this->setSuccess('Successfully save data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to ', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete {{$variableName}} from database
	 * 
	 * @param bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$activityLog = $this->getModel();
			$force ?
				$activityLog->forceDelete() :
				$activityLog->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete data');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete ', $error);
		}

		return $this->returnResponse();
	}
}
