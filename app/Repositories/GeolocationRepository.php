<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

class GeolocationRepository extends BaseRepository
{
	/**
	 * Country container
	 * 
	 * @var string
	 */
	private $country;

	/**
	 * Currency container
	 * 
	 * @var int
	 */
	private $currency;

	/**
	 * Detect country of the user
	 * 
	 * @return string
	 */
	public function detectCountry()
	{
		//
	}

	/**
	 * Get currency for certain country
	 * 
	 * @param  string|null  $country
	 * @return int
	 */
	public function getCurrency()
	{
		//
	}
}
