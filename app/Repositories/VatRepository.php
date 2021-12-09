<?php

namespace App\Repositories;

use App\Repositories\Base\BaseRepository;
use Ibericode\Vat\{ 
	Validator, 
	Geolocator, 
	Rates,
	Countries,
	Exception 
};

class VatRepository extends BaseRepository
{
	/**
	 * VAT Validator class container
	 * 
	 * @var \Ibericode\Vat\Validator|null
	 */
	private $validator;

	/**
	 * VAT Geolocator class container
	 * 
	 * @var \Ibericode\Vat\Geolocator|null
	 */
	private $geolocator;

	/**
	 * VAT Rate class container
	 * 
	 * @var \Ibericode\Vat\Geolocator|null
	 */
	private $rates;

	/**
	 * VAT Countries class container
	 * 
	 * @var array
	 */
	private $countries;

	/**
	 * VAT Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->validator = new Validator();
		$this->geolocator = new Geolocator();
		$this->rates = new Rates(storage_path('framework/cache/data/vat.txt'));
		$this->countries = new Countries();
	}

	/**
	 * Validate VAT number
	 * 
	 * @param string  $vatNumber
	 * @return bool
	 */
	public function validateVatNumber(string $vatNumber)
	{
		return $this->validator->validateVatNumber($vatNumber);
	}

	/**
	 * Validate VAT format correct
	 * 
	 * @param string  $vatNumber
	 * @return bool
	 */
	public function isVatFormatCorrect(string $vatNumber)
	{
		return $this->validator->validateVatNumberFormat($vatNumber);
	}

	/**
	 * List country code and name for VAT
	 * 
	 * @return array
	 */
	public function countries()
	{
		return $this->countries;
	}

	/**
	 * Get VAT country rate
	 * 
	 * @param string  $vatNumber
	 * @return 
	 */
	public function getCountryRate(string $vatNumber)
	{
		if (! $this->isVatFormatCorrect($vatNumber)) {
			return 0;
		}

		return $this->rates->getRateForCountryOnDate($vatNumber, now(), 'standard');
	}

	/**
	 * Locate the user from accessing IP Address
	 * 
	 * @param string  $ipAddress
	 * @return string
	 */
	public function locateIpAddress(string $ipAddress)
	{
		return $this->geolocator->locateIpAddress($ipAddress);
	}
}
