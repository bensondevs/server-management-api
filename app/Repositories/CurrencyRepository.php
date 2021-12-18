<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

class CurrencyRepository extends BaseRepository
{
	/**
	 * Country location container
	 * 
	 * @var string
	 */
	private $country; 

	/**
	 * Detect current geolocation
	 * 
	 * @return string
	 */
	public function detectGeolocation()
	{
		//
	}

	/**
	 * Find currency by 
	 * 
	 */
}
