<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\Pricing;

use App\Enums\Pricing\PricingCurrency;

class PricingRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new Pricing);
	}

	public function currencyOptions()
	{
		return PricingCurrency::asSelectArray();
	}

	public function save(array $pricingData = [])
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

	public function delete(bool $force = false)
	{
		try {
			$pricing = $this->getModel();
			$force ?
				$pricing->forceDelete() :
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
