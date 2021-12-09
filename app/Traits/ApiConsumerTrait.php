<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ApiConsumerTrait 
{
	/**
	 * Headers of application
	 * 
	 * @var array
	 */
	protected $headers = [
		'Accept' => 'application/json',
		'Content-Type' => 'application/json',
	];

	/**
	 * Set headers of the request
	 * 
	 * @param array
	 * @return $this
	 */
	public function setHeaders(array $headers)
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * Merge new headers to the headers array
	 * 
	 * @param array
	 * @return $this
	 */
	public function mergeHeaders(array $headers)
	{
		$this->headers = array_merge($this->headers, $headers);

		return $this;
	}

	/**
	 * Set header according to key and value
	 * 
	 * @param string  $key
	 * @param mixed  $value
	 * @return $this
	 */
	public function setHeader(string $key, $value)
	{
		$this->headers[$key] = $value;

		return $this;
	}

	/**
	 * Execute get request and return response as array
	 * 
	 * @param string  $url
	 * @return array
	 */
	public function apiGet(string $url)
	{
		$response = Http::withHeaders($this->headers)->get($url);
		$this->response = $response;

		return [
			'body' => $response->body(),
			'data' => $response->json(),
			'status' => $response->getStatusCode(),
		];
	}

	/**
	 * Execute post request and return response as array
	 * 
	 * @param string $url
	 * @param array  $parameters
	 * @return array
	 */
	public function apiPost(string $url, array $parameters)
	{
		$response = Http::withHeaders($this->headers)->post($url, $parameters);
		$this->response = $response;

		return [
			'body' => $response->body(),
			'data' => $response->json(),
			'status' => $response->getStatusCode(),
		];
	}

	/**
	 * Execute patch request and return response as array
	 * 
	 * @param string  $url
	 * @param array  $parameters
	 * @return array
	 */
	public function apiPatch(string $url, array $parameters)
	{
		$response = Http::withHeaders($this->headers)->patch($url, $parameters);
		$this->response = $response;

		return [
			'body' => $response->body(),
			'data' => $response->json(),
			'status' => $response->getStatusCode(),
		];
	}

	/**
	 * Execute put request and return response as array
	 * 
	 * @param string  $url
	 * @param array  $parameters
	 * @return array
	 */
	public function apiPut(string $url, array $parameters)
	{
		$response = Http::withHeaders($this->headers)->put($url);
		$this->response = $response;

		return [
			'body' => $response->body(),
			'data' => $response->json(),
			'status' => $response->getStatusCode(),
		];
	}

	/**
	 * Execute delete request and return response as array
	 * 
	 * @param string  $url
	 * @param array  $parameters
	 * @return array
	 */
	public function apiDelete(string $url, array $parameters)
	{
		$response = Http::withHeaders($this->headers)->delete($url);
		$this->response = $response;

		return [
			'body' => $response->body(),
			'data' => $response->json(),
			'status' => $response->getStatusCode(),
		];
	}
}