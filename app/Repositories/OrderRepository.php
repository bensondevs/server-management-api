<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Jobs\SendMail;

use App\Mail\Orders\OrderPlacedMail;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderPlan;
use App\Models\OrderAddon;
use App\Models\ServicePlan;
use App\Models\ServiceAddon;

class OrderRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Order);
	}

	public function ofUser(User $user)
	{
		$orders = $this->getModel()
			->with([
				'servicePlan',
				'container.subnetIp',
				'container.server.datacenter.region'
			])
			->where('customer_id', $user->id)
			->get();

		$this->setCollection($orders);

		return $this->getCollection();
	}

	public function allWithData(array $options)
	{
		$orders = Order::with(['container', 'customer'])->orderByDesc('order_number');
		$this->setModel($orders);

		$this->all($options);
		return $this->getCollection();
	}

	public function containerless()
	{
		$orders = Order::doesnthave('container')
			->with('customer')
			->get();

		return $this->setCollection($orders);
	}

	public function find($id)
	{
		$order = $this->getModel()->find($id);
		$this->setModel($order);

		return $this->getModel();
	}

	public function findWith($id, array $relations)
	{
		$order = $this->getModel()
			->with($relations)
			->find($id);
		$this->setModel($order);

		return $this->getModel();
	}

	public function setOrderAddons(Order $order, array $addons)
	{
		// Cancel if no added addons
		if (! $orderAddons = $addons) return null;

		// Prepare all addons data
		$serviceAddonIds = [];
		foreach ($orderAddons as $_orderAddon) {
			array_push(
				$serviceAddonIds, 
				$_orderAddon['service_addon_id']
			);
		}
		$serviceAddons = ServiceAddon::whereIn('id', $serviceAddonIds)->get();

		// Prepare Addons Data
		$rawAddons = [];
		foreach ($orderAddons as $key => $orderAddon) {
			// Query to collection not DB --- FASTER
			$selectedAddon = $serviceAddons
				->where('id', $orderAddon['service_addon_id'])
				->first();
			
			if ($selectedAddon) {
				// Prepare Raw Addon
				$rawAddon = [
					'id' => generateUuid(),
					'order_id' => $order->id,
					'service_addon_id' => $selectedAddon->id,
					'quantity' => $orderAddon['quantity'],
					'currency' => $selectedAddon->currency,
					'current_fee' => $selectedAddon->addon_fee,
					'amount' => $selectedAddon->addon_fee * $orderAddon['quantity'],
					'note' => isset($orderAddon['note']) ? 
						$orderAddon['note'] : 
						null,
				];
				array_push($rawAddons, $rawAddon);
			}
		}

		return OrderAddon::insert($rawAddons);
	}

	public function save(array $orderData)
	{
		try {
			$order = $this->getModel();
			$order->fill($orderData['order_data']);
			$order->meta_container = $orderData['order_data']['meta_container'];
			$order->save();

			// Set Order Plan
	        $orderPlan = new OrderPlan($orderData['plan_data']);
	        $order->plan()->save($orderPlan);
				
			// Set Order Addons
			if (isset($orderData['addons_list'])) {
				$this->setOrderAddons($order, $orderData['addons_list']);
			}

			$this->setModel($order);

			$this->setSuccess('Successfully save order data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save order data.', $error);
		}

		return $this->getModel();
	}

	public function place(array $placeOrderData)
	{
		try {
			// Prepare Order data
			$orderData = $placeOrderData;
			// unset($orderData['addons_list']);

			// Prepare VAT Size percentage
			$user = User::findOrFail($orderData['order_data']['customer_id']);
			$vatSizePercentage = $user->vat_size_percentage;

			DB::beginTransaction();

			// Create Order
			$order = new Order;
			$order->fill($orderData['order_data']);
			$order->vat_size_percentage = $vatSizePercentage;
			$order->meta_container = $orderData['meta_container'];
			$order->save();

			/*
				SELECTED SERVICE PLAN
			*/
			$orderPlan = new OrderPlan([
				'order_id' => $order->id,
				'service_plan_id' => $orderData['plan_data']['service_plan_id'],
				'quantity' => $orderData['plan_data']['quantity'],
				'note' => isset($orderData['plan_data']['note']) ?
					$orderData['plan_data']['note'] : null,
			]);
			$orderPlan->save();

			/*
				ADDONS ADDITION
			*/
			if (isset($orderData['addons_list'])) {
				$order = $this->setOrderAddons($order, $orderData['addons_list']);
			}

			DB::commit();
			
			$this->setModel($order);

			$this->setSuccess('Successfully place order data');
		} catch (QueryException $qe) {
			DB::rollBack();
			$error = $qe->getMessage();
			$this->setError('Failed to place order.', $error);
		}

		return $this->getModel();
	}

	public function reOrder(Order $prevOrder)
	{
		try {
			DB::beginTransaction();

			// Create new order
			$newOrder = $this->getModel();
			$newOrder->status = 'unpaid';
			$newOrder->customer_id = $prevOrder->customer_id;
			$newOrder->vat_size_percentage = $prevOrder->customer->vat_size_percentage;
			$newOrder->save();

			// Set the old plan to new order
			$oldPlan = $prevOrder->plan;
			$servicePlan = $oldPlan->servicePlan;
			$newOrder->plan()->save(new OrderPlan([
				'service_plan_id' => $servicePlan->id,
				'quantity' => 1,
				'note' => 'Reorder from Order #' . $oldPlan->order_number,
			]));

			$newOrder->countTotal(); // Count the total

			DB::commit();

			$this->setModel($newOrder);

			$this->setSuccess('Successfully reorder.');
		} catch (QueryException $qe) {
			DB::rollback();
			$error = $qe->getMessage();
			$this->setError('Failed to reorder.', $error);
		}

		return $this->getModel();
	}

	public function process()
	{
		try {
			$order = $this->getModel();
			$order->process();

			$this->setSuccess('Successfully process order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed process order.', $error);
		}

		return $this->getModel();
	}

	public function changeStatus($status = 0)
	{
		try {
			$order = $this->getModel();
			$order->status = $status;
			$order->save();

			$this->setModel($order);

			$this->setSuccess('Successfully change order status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change order status', $error);
		}

		return $this->getModel();
	}

	public function delete()
	{
		try {
			$order = $this->getModel();
			$order->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete order', $error);
		}

		return $this->returnResponse();
	}
}
