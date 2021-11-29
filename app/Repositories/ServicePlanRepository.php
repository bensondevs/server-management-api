<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\ServicePlan;

class ServicePlanRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new ServicePlan);
	}

	public function find($id)
	{
		$plan = ServicePlan::findOrFail($id);
		$this->setModel($plan);

		return $this->getModel();
	}

	public function changeStatus($newStatus)
	{
		try {
			$plan = $this->getModel();
			$plan->status = $newStatus;
			$plan->save();

			$this->setModel($plan);

			$this->setSuccess('Successfully change service plan status.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change service plan status', $error);
		}

		return $this->getModel();
	}

	public function save(array $servicePlanData)
	{
		try {
			$plan = $this->getModel();
			$plan->fill($servicePlanData);
			$plan->duration = $servicePlanData['time_quantity'] . ' ' . $servicePlanData['time_unit'];
			$plan->save();

			$pricing = $plan->addPricing([
				'currency' => isset($servicePlanData['currency']) ? $servicePlanData['currency'] : 1,
				'price' => isset($servicePlanData['price']) ? $servicePlanData['price'] : 0,
			]);

			$this->setModel($plan);

			$this->setSuccess('Successfully save service plan data');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save service plan data.', $error);
		}

		return $this->getModel();
	}

	public function select($planId)
	{
		$plan = $this->find($planId);
		return [
			'plan_name' => $plan->plan_name,
			'duration' => $plan->duration,
			'amount' => currency_format($plan->subscription_fee, $plan->currency),
		];
	}

	public function delete()
	{
		try {
			$plan = $this->getModel();
			$plan->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete service plan');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete service plan', $error);
		}

		return $this->returnResponse();
	}
}
