<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Order, OrderItem };

class OrderItemRepository extends BaseRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new OrderItem);
	}

	/**
	 * Save order item
	 * 
	 * @param array  $data
	 * @return \App\Models\OrderItem
	 */
	public function save(array $data = [])
	{
		try {
			$orderItem = $this->getModel();
			$orderItem->fill($data);
			$orderItem->save();

			$this->setModel($orderItem);

			$this->setSuccess('Successfully save order item.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save order item', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete order item
	 * 
	 * @param bool  $force
	 * @return bool
	 */
	public function delete(bool $force = false)
	{
		try {
			$orderItem = $this->getModel();
			$orderItem->delete();
				
			$this->destroyModel();

			$this->setSuccess('Successfully delete data');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete ', $error);
		}

		return $this->returnResponse();
	}
}
