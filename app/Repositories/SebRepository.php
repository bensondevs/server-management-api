<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;
use App\Traits\ApiConsumerTrait;

class SebRepository extends BaseRepository
{
	use ApiConsumerTrait;

	protected $baseUrl;
	protected $uri;

	public function __construct()
	{
		$this->setHeader('Accept', 'application/json');
		$basicToken = env('SEB_USERNAME') . ':' . env('SEB_SECRET');
		$this->setHeader('Authorization', 'Basic ' . base64_encode($basicToken));

		$this->baseUrl = env('SEB_API_URL_TEST');
	}

	public function setUri(string $uri)
	{
		return $this->setUrl($this->baseUrl . $uri);
	}

	public function payOnce(array $parameters)
	{
		$this->setUri('/payments/oneoff');
		$this->setRequestType('post');
		$this->setPayloads($parameters);

		return $this->getResponse();
	}

	public function checkPayment($paymentReference)
	{
		$this->setUri('/payments/' . $paymentReference);
		$this->setRequestType('get');
		$this->setPayloads(['api_username' => env('SEB_USERNAME')]);

		return $this->getResponse();
	}
}
