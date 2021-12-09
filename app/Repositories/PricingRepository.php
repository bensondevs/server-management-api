<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\Pricing;
use App\Enums\Currency;

class PricingRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Pricing);
	}

	/**
	 * Save pricing data
	 * 
	 * @param array  $pricingData
	 * @return \App\Models\Pricing
	 */
	public function save(array $pricingData)
	{
		try {
			$pricing = $this->getModel();
			$pricing->priceable_type = $pricingData['priceable_type'];
			$pricing->priceable_id = $pricingData['priceable_id'];
			$pricing->currency = $pricingData['currency'];
			$pricing->price = $pricingData['price'];
			$pricing->save();

			$this->setModel($pricing);

			$this->setSuccess('Successfully save pricing data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save pricing.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete pricing data
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$pricing = $this->getModel();
			$pricing->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully delete pricing.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delete pricing.', $error);
		}

		return $this->returnResponse();
	}
}
