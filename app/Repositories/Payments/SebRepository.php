<?php

namespace App\Repositories\Payments;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;
use App\Traits\ApiConsumerTrait;

use App\Models\{ Payment, SebPayment };
use App\Enums\Payment\Seb\{
	SebPaymentStatus as Status,
	SebPaymentApiResponse as ApiResponseStatus
};

class SebRepository extends BaseRepository
{
	use ApiConsumerTrait;

	/**
	 * Base URL of the SEB Payment
	 * 
	 * @var string|null
	 */
	protected $baseUrl;

	/**
	 * Controller constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new SebPayment);
	}

	/**
	 * Set authentication token in request header 
	 * before doing the request
	 * 
	 * @param string  $uri
	 * @return void
	 */
	protected function authenticate()
	{
		$token = base64_encode(config('seb.basic_token'));
		$authorization = 'Basic ' . $token;
		$this->setHeader('Authorization', $authorization);
	}

	/**
	 * Prepare needed arguments for the request parameter of SEB
	 * Including username, account name and landing page url
	 * 
	 * @param array  $parameters
	 * @return array
	 */
	protected function prepareParameters(array $parameters)
	{
		return array_merge($parameters, [
			'api_username' => config('seb.api_username'),
			'account_name' => config('seb.account_name'),
			'customer_url' => config('seb.customer_url'),
		]);
	}

	/**
	 * Create SEB Url from only supplied argument of URI
	 * 
	 * @param string  $uri
	 * @return string
	 */
	private function _url(string $uri)
	{
		$baseUrl = config('seb.base_url');
		if (last_character($baseUrl) !== '/') {
			$baseUrl .= '/';
		}

		if (first_character($uri) === '/') {
			$uri = substr($uri, 1);
		}

		return $baseUrl . $uri;
	}

	/**
	 * Create SEB Payment record in database 
	 * by supplied data of payment
	 * 
	 * @param \App\Models\Payment  $payment
	 * @return \App\Models\SebPayment
	 */
	public function create(Payment $payment)
	{
		try {
			$sebPayment = $this->getModel();
			$sebPayment->payment()->associate($payment);
			$sebPayment->order_reference = $payment->order_id;
			$sebPayment->payment_reference = $payment->id;
			$sebPayment->amount = $payment->amount;
			$sebPayment->save();

			$this->setModel($sebPayment);

			$this->setSuccess('Successfully create SEB Payment record.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create SEB payment record.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Execute pay once for the SEB Payment and create SEB Payment record
	 * into the database with relation with supplied payment model. This function
	 * returns the URL link to 
	 * 
	 * @param array  $parameters
	 * @return \App\Models\SebPayment
	 */
	public function payOnce(array $parameters)
	{
		// Set authentication headers of the request
		$this->authenticate();

		$sebPayment = $this->getModel();

		// Prepare parameters of the request
		$parameters = $this->prepareParameters($parameters);
		$parameters['payment_reference'] = $sebPayment->payment_id;
		$parameters['order_reference'] = $sebPayment->order_reference;
		$parameters['email'] = $sebPayment->payment->user->email;
		$parameters['customer_ip'] = request()->ip();
		$parameters['timestamp'] = now();

		// Execute request and save the response
		try {
			$url = $this->_url('payments/oneoff');
			$response = $this->apiPost($url, $parameters);
			$sebPayment->captureResponse($response['data']);

			$this->setModel($sebPayment);

			$this->setSuccess('Successfully send request to execute payment to SEB.');
		} catch (\Throwable $th) {
			$error = $th->getMessage();
			$this->setError('Failed to send request to execute payment to SEB.');
		}

		return $this->getModel();
	}

	/**
	 * Syncronise payment from database to the SEB
	 * 
	 * @param string  $reference
	 * @return array
	 */
	public function checkPayment()
	{
		// Set authentication headers of the request
		$this->authenticate();

		// Execute check request and save the response
		try {
			$sebPayment = $this->getModel();

			$reference = $sebPayment->order_reference;
			$url = $this->_url('payments/' . $reference);
			$response = $this->apiGet($url);
			$sebPayment->captureResponse($response['data']);

			$this->setModel($sebPayment);

			$this->setSuccess('Successfully check payment to SEB.');
		} catch (\Throwable $th) {
			$error = $th->getMessage();
			$this->setError('Failed to execute check payment to SEB.', $error);
		}

		return $this->getModel();
	}
}