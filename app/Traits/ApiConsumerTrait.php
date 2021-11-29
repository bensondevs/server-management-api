<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ApiConsumerTrait 
{
	protected $headers = [];
	protected $requestType;
	protected $url;
	protected $payloads = [];
	protected $returnType = 'json';
	public $response;

	public function setHeaders(array $headers)
	{
		return $this->headers = $headers;
	}

	public function getHeaders()
	{
		return $this->headers;
	}

	public function pushHeaders(array $headers)
	{
		$arrayKeys = array_keys($headers);
		foreach ($arrayKeys as $arrayKey)
			$this->headers[$arrayKey] = $headers[$arrayKey];

		return $this->getHeaders();
	}

	public function setHeader($key, $value)
	{
		return $this->headers[$key] = $value;
	}

	public function setRequestType(string $type)
	{
		return $this->requestType = strtolower($type);
	}

	public function getRequestType()
	{
		return $this->requestType;
	}

	public function setUrl(string $url)
	{
		return $this->url = $url;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function setParameters(array $parameters)
	{
		$this->setPayloads($parameters);
	}

	public function setParameter(string $parameterKey, $parameterValue)
	{
		$this->setPayload($parameterKey, $parameterValue);
	}

	public function setPayloads(array $payloads)
	{
		return $this->payloads = $payloads;
	}

	public function setPayload(string $payloadName, $payloadValue)
	{
		return $this->payloads[$payloadName] = $payloadValue;
	}

	public function pushPayloads(array $payloads)
	{
		$arrayKeys = array_keys($paylods);
		foreach ($arrayKeys as $arrayKey)
			$this->payloads[$arrayKey] = $payloads[$arrayKey];

		return $this->getHeaders();
	}

	public function getPayloads()
	{
		return $this->payloads;
	}

	public function setReturnType(string $type)
	{
		return $this->returnType = $type;
	}

	public function getReturnType()
	{
		return $this->returnType;
	}

	public function getResponse()
	{
		// Set headers if exist
		$client = Http::withHeaders($this->headers);

		// Do request
		$client = ($this->payloads) ?
			$client->{$this->getRequestType()}(
				$this->getUrl(), 
				$this->getPayloads()
			) :
			$client->{$this->getRequestType()}(
				$this->getUrl()
			);

		$statusCode = $client->getStatusCode();
		if ($statusCode >= 200 && $statusCode < 300)
			$this->response = $client->{$this->getReturnType()}();
		else
			abort($statusCode, $client->body());

		return $this->response;
	}
}