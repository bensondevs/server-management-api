<?php

namespace App\Repositories;

use \Ibericode\Vat\Exception;

use \Ibericode\Vat\Validator;
use \Ibericode\Vat\Geolocator;

use \Ibericode\Vat\Rates;
use \Ibericode\Vat\Countries;

use App\Repositories\Base\BaseRepository;

class VatRepository extends BaseRepository
{
	private $validator;
	private $geolocator;

	private $rates;
	private $countries;

	public function __construct()
	{
		$this->validator = new Validator();
		$this->geolocator = new Geolocator();

		$this->rates = new Rates(storage_path('framework/cache/data/vat.txt'));
		$this->countries = new Countries();
	}

	public function isVatExist(string $vatNumber)
	{
		return $this->validator->validateVatNumber($vatNumber);
	}

	public function isVatFormatCorrect(string $vatNumber)
	{
		return $this->validator->validateVatNumberFormat($vatNumber);
	}

	public function getCountryCode(string $countryName)
	{
		$countryName = strtolower($countryName);
		foreach ($this->countries as $code => $name) {
			$name = strtolower($name);
			
			if ($countryName == $name) return $code;
		}

		return null;
	}

	public function getCountryRate(string $vatNumber)
	{
		if (! $this->isVatFormatCorrect($vatNumber)) 
			return 0;

		return $this->rates->getRateForCountryOnDate(
			$vatNumber, 
			carbon()->now(),
			'standard'
		);
	}

	public function locateIpAddress(string $ipAddress)
	{
		return $this->geolocator->locateIpAddress($ipAddress);
	}
}
