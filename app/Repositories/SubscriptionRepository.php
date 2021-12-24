<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Subscription, Order };

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
	 * @return 
	 */
	public function renew()
	{
		//
	}

	/**
	 * Renew multiple subscription as one order.
	 * 
	 * @param
	 * @return
	 */
	public function renewMultiple()
	{
		//
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
