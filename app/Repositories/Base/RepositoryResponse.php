<?php

namespace App\Repositories\Base;

use Illuminate\Http\Response;

trait RepositoryResponse 
{
	/**
	 * Default HTTP Status
	 * 
	 * @var int
	 */
	public $httpStatus = 200;
	
	/**
	 * Status of execution
	 * 
	 * @var string
	 */
	public $status;

	/**
	 * Collection of execution statuses
	 * 
	 * @var array
	 */
	public $statuses = [];

	/**
	 * Message as response of execution
	 * 
	 * @var string
	 */
	public $message;

	/**
	 * Collection of execution messages
	 * 
	 * @var string
	 */
	public $messages = [];

	/**
	 * Query error of the execution
	 * 
	 * @var string
	 */
	public $queryError;

	/**
	 * Collection of query errors
	 * 
	 * @var array
	 */
	public $queryErrors = [];

	/**
	 * Flash status and message to front-end via session
	 * 
	 * @return void
	 */
	public function flash()
	{
		session()->flash($this->status, $this->message);
	}

	/**
	 * Set the response status of execution
	 * 
	 * @param  string  $status
	 * @return void
	 */
	public function setResponseStatus(string $status = 'error')
	{
		$this->status = $status;
		$this->statuses[] = $status;
	}

	/**
	 * Set HTTP status code of the respose
	 * 
	 * @param int $code
	 * @return void
	 */
	public function setHttpStatusCode(int $code)
	{
		$this->httpStatus = $code;
	}

	/**
	 * Set execution message
	 * 
	 * @param string $message
	 * @return void
	 */
	public function setMessage(string $message = 'Unknown')
	{
		$this->message = $message;
		$this->messages[] = $message;
	}

	/**
	 * Set query error of the execution
	 * 
	 * @param string $queryError
	 * @return void
	 */
	public function setQueryError(string $queryError = '')
	{
		$this->queryError = $queryError;
		$this->queryErrors[] = $queryError;
	}

	/**
	 * Set response as unprocessed input
	 * 
	 * @param string $message
	 * @return void
	 */
	public function setUnprocessedInput(string $message = 'Wrong input')
	{
		$this->setResponseStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 422;
	}

	/**
	 * Set response as forbidden
	 * 
	 * @param string $message
	 * @return void 
	 */
	public function setForbidden(string $message = 'Forbidden')
	{
		$this->setResponseStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 403;
	}

	/**
	 * Set response as not found
	 * 
	 * @param string $message
	 * @return void
	 */
	public function setNotFound(string $message = 'Not found')
	{
		$this->setResponseStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 404;
	}

	/**
	 * Set response as success
	 * 
	 * @param string $message
	 * @return void
	 */
	public function setSuccess(string $message = 'Success')
	{
		$this->setResponseStatus('success');
		$this->setMessage($message);
		$this->httpStatus = 200;

		if (request()->isMethod('POST')) {
			$this->httpStatus = 201;
		}

		if (request()->isMethod('PUT')) {
			$this->httpStatus = 204;
		}
	}

	/**
	 * Set response as error
	 * 
	 * @param string $message
	 * @param string $queryError
	 * @return void
	 */
	public function setError(string $message = 'Error', string $queryError)
	{
		$this->setResponseStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 500;
		$this->setQueryError($queryError);
	}

	/**
	 * Set custom error as response
	 * 
	 * @param string $message
	 * @param int $errorCode
	 * @param string $queryError
	 * @return void
	 */
	public function setCustomError(string $message, int $errorCode, string $queryError = '')
	{
		$this->setResponseStatus('error');
		$this->setMessage($message);
		$this->httpStatus = $errorCode;
		$this->setQueryError($queryError);
	}

	/**
	 * Return response foe the execution
	 * 
	 * @param mixed $data
	 * @return bool
	 */
	public function returnResponse($data = true)
	{
		return ($this->status == 'success') ? $data : false;
	}
}