<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Order, OrderItem };

class OrderRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Order);
	}

	/**
	 * Save order
	 * 
	 * @param array  $orderData
	 * @return \App\Models\Order
	 */
	public function save(array $orderData)
	{
		try {
			$order = $this->getModel();
			$order->fill($orderData);
			$order->save();

			$this->setModel($order);

			$this->setSuccess('Successfully create order.');
		} catch (QueryException $e) {
			$error = $qe->getMessage();
			$this->setError('Failed to create order', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add order item
	 * 
	 * @param  array  $orderItemData
	 * @return \App\Models\OrderItem
	 */
	public function addItem(array $orderItem)
	{
		try {
			$order = $this->getModel();
			$order->items()->save(new OrderItem($orderItem));

			$this->setModel($order);

			$this->setSuccess('Successfully add item to order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to add item to order.');	
		}

		return $this->getModel();
	}

	/**
	 * Remove order item
	 * 
	 * @param  \App\Models\OrderItem  $item
	 * @return bool
	 */
	public function removeItem(OrderItem $item)
	{
		try {
			$item->delete();

			$this->setSuccess('Successfully remove item from order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to remove item from order.');
		}

		return $this->returnResponse();
	}

	/**
	 * Delete order
	 *
	 * @return bool
	 */
	public function delete()
	{
		$order = $this->getModel();
		$order->delete();
		$this->setSuccess('Successfully delete order.');

		return $this->returnResponse();
	}
}
