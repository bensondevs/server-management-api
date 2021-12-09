<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Server, Datacenter };

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

	public function toggleStatus()
	{
		try {
			$server = $this->getModel();

			// Toggle to server


			$server->toggleStatus();
			$server->ip_address = $server->ip_address;
			$this->model = $server;

			$this->setSuccess('Successfully change server status');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change server status.', $error);
		}

		return $this->getModel();
	}

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
