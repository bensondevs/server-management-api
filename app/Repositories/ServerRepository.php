<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Server, Datacenter };
use App\Enums\Server\ServerStatus;

class ServerRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setModel(new Server());
	}

	/**
	 * Save server data
	 * 
	 * @param array  $serverData
	 * @return \App\Models\Server
	 */
	public function save(array $serverData)
	{
		try {
			$server = $this->getModel();
			$server->fill($serverData);
			$server->ip_address = $serverData['ip_address'];
			$server->save();
			
			$this->setModel($server);

			$this->setSuccess('Successfully save server data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save server data', $error);
		}

		$this->flash($this->status, $this->message);

		return $this->getModel();
	}

	/**
	 * Change status of the server
	 * 
	 * @param  int  $status
	 * @return  \App\Models\Server
	 */
	public function changeStatus(int $status = 1)
	{
		try {
			$server = $this->getModel();
			$server->setStatus($status);
			
			$this->setModel($server);

			$this->setSuccess('Successfully change server status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change server status.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Activate the server
	 * 
	 * @return  \App\Models\Server
	 */
	public function activate()
	{
		try {
			$server = $this->getModel();
			$server->setStatus(ServerStatus::Active);
			$server->save();

			$this->setModel($server);

			$this->setSuccess('Successfully activate server.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to activate the server.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Deactivate the server
	 * 
	 * @return  \App\Models\Server
	 */
	public function inactivate()
	{
		try {
			$server = $this->getModel();
			$server->setStatus(ServerStatus::Inactive);
			$server->save();

			$this->setModel($server);

			$this->setSuccess('Successfully inactivate the server.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to inactivate the server.', $error);
		}
	}

	/**
	 * Delete the server
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$server = $this->getModel();
			$server->delete();
			$this->destroyModel();

			$this->setSuccess('Successfully delete server.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delet server', $error);
		}

		return $this->returnResponse();
	}
}
