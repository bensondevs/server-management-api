<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Subscription, Order, OrderItem };

class SubscriptionRepository extends BaseRepository
{
	/**
	 * Repository class constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Subscription);
	}

	/**
	 * Create subscription
	 * 
	 * @param Order  $order
	 * @return \App\Models\Subscription
	 */
	public function create(Order $order)
	{
		try {
			//
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create subscription.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Start subscription
	 * 
	 * @param  
	 * @return \App\Models\Subscription
	 */
	public function start()
	{
		//
	}

	/**
	 * Renew subscription by creating new order
	 * to be paid by user.
	 * 
	 * @return bool
	 */
	public function createRenewalOrder()
	{
		// Begin database transaction
		// This will allow bulky action to database
		// Due to multiple actions executed within this function
		// This will help to cancel all the actions effect if there is problematic
		// process amongst all processes
		DB::beginTransaction();

		try {
			$subscription = $this->getModel();

			// Create order
			$order = Order::create(['user_id' => $subscription->user_id]);

			// Add subscription as item to order
			$orderItem = new OrderItem(['quantity' => 1]);
			$orderItem->for($order);
			$orderItem->setSubscription($subscription);
			$orderItem->save();

			// After all necessary actions executed correctly without problem
			// Just commit the change to the database
			DB::commit();

			$this->setSuccess('Successfully create new renewal order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create renewal order for selected subscription.', $error);

			// If the failure happen, just rollback all the changes
			// This will return the state of database to before current method is executed
			DB::rollback();
		}

		return $this->returnResponse();
	}

	/**
	 * Renew multiple subscription as one order.
	 * 
	 * @param   \Illuminate\Support\Collection  $subscriptions
	 * @return  bool
	 */
	public function createMultipleRenewalOrder(Collection $subscriptions)
	{
		// Begin database transaction
		// This will allow bulky action to database
		// Due to multiple actions executed within this function
		// This will help to cancel all the actions effect if there is problematic
		// process amongst all processes
		DB::beginTransaction();

		try {
			// Create order
			$order = Order::create([
				'user_id' => $subscriptions->first()->user_id
			]);

			// Add subscriptions as items to order
			foreach ($subscriptions as $subscription) {
				$orderItem = new OrderItem(['quantity' => 1]);
				$orderItem->for($order);
				$orderItem->setSubscription($subscription);
				$orderItem->save();
			}

			// After all necessary actions executed correctly without problem
			// Just commit the change to the database
			DB::commit();

			$this->setSuccess('Successfully create multiple renewal order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create multiple renewal order for selection subscriptions.', $error);

			// If the failure happen, just rollback all the changes
			// This will return the state of database to before current method is executed
			DB::rollback();
		}

		return $this->returnResponse();
	}

	/**
	 * Add the end time of the subscription from the order
	 * model of the type of renewal
	 * 
	 * @param  \App\Models\Order
	 */
	public function processRenewal(Order $order)
	{
		try {
			foreach ($order->items as $item) {
				$subscription = $item->itemable;
				$subscribeable = $subscription->subscribeable;
				$subscription->prolong($subscribeable->duration_days);
			}

			$this->setSuccess('Successfully process renewal for subscriptions.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to process renewal for subscriptions', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Prolong the subscription
	 * 
	 * @param mixed  $additionalTime
	 * @return \App\Models\Subscription
	 */
	public function prolong($additionTime)
	{
		//
	}

	/**
	 * Destroy subscription
	 * 
	 * @return bool
	 */
	public function destroy()
	{
		//
	}
}
