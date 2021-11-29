<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\Server;
use App\Models\Datacenter;

use App\Repositories\Base\BaseRepository;

class ServerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setModel(new Server());
	}

	public function find($id)
	{
		$this->setModel(Server::findOrFail($id));
		return $this->getModel();
	}

	public function findWith($id, $relation = '')
	{
		$this->setModel(
			Server::with($relation)->findOrFail($id)
		);

		return $this->getModel();
	}

	public function mostSelectedOf(Datacenter $datacenter)
	{
		// Select least chosen server
		$server = Server::withCount('containers')
			->where('datacenter_id', $datacenter->id)
			->get()
			->sortByDesc('containers_count')
			->first();

		$this->setModel($server);

		return $this->getModel();
	}

	public function leastSelectedOf(Datacenter $datacenter)
	{
		// Select least chosen server
		return Server::withCount('containers')
			->where('datacenter_id', $datacenter->id)
			->get()
			->sortBy('containers_count')
			->first();
	}

	public function allWithData()
	{
		$servers = Server::with(['datacenter.region'])
			->withCount(['containers'])
			->get();
		$this->setCollection($servers);

		return $this->getCollection();
	}

	public function save($serverData)
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
