<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;
use App\Models\{ ServicePlan, ServicePlanItem, Pricing };

class ServicePlanRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new ServicePlan);
	}

	/**
	 * Save service plan
	 * 
	 * @param array  $servicePlanData
	 * @return \App\Models\ServicePlan
	 */
	public function save(array $servicePlanData)
	{
		try {
			$plan = $this->getModel();
			$plan->fill($servicePlanData);
			$plan->save();

			$this->setModel($plan);

			$this->setSuccess('Successfully save service plan.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save service plan data.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete service plan
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$plan = $this->getModel();
			$plan->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete service plan.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete service plan', $error);
		}

		return $this->returnResponse();
	}

	/**
	 * Set service plan price
	 * 
	 * @param float  $price
	 * @param int  $currencyEnum
	 * @return \App\Models\ServicePlan
	 */
	public function setPrice(float $price, int $currencyEnum = 1)
	{
		try {
			$plan = $this->getModel();
			$pricing = Pricing::create([
				'pricingable_id' => $plan->id,
				'pricingable_type' => ServicePlan::class,
				'price' => $price, 
				'currency' => $currencyEnum
			]);

			$this->setModel($plan);

			$this->setSuccess('Successfully set price for a service plan.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set price.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Add item to service plan
	 * 
	 * @param array  $itemData
	 * @return \App\Models\ServicePlan 
	 */
	public function addItem(array $itemData)
	{
		//
	}

	/**
	 * Remove item from service plan
	 * 
	 * @param \App\Models\ServicePlanItem  $item
	 * @return bool
	 */
	public function removeItem(ServicePlanItem $item)
	{
		//
	}
}
