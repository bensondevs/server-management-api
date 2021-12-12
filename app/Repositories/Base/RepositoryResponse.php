<?php

namespace App\Repositories\Base;

use Illuminate\Http\Response;

trait RepositoryResponse 
{
	public $httpStatus = 200;
	
	public $status;
	public $statuses = [];

	public $message;
	public $messages = [];

	public $queryError;
	public $queryErrors = [];

	public function flash()
	{
		session()->flash($this->status, $this->message);
	}

	public function setStatus($status = 'error')
	{
		$this->status = $status;
		$this->statuses[] = $status;
	}

	public function setHttpStatusCode(int $code)
	{
		return $this->httpStatus = $code;
	}

	public function setMessage($message = 'Unknown')
	{
		$this->message = $message;
		$this->messages[] = $message;
	}

	public function setQueryError(string $queryError = '')
	{
		$this->queryError = $queryError;
		$this->queryErrors[] = $queryError;
	}

	public function setUnprocessedInput($message = 'Wrong input')
	{
		$this->setStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 422;

		return null;
	}

	public function setForbidden($message = 'Forbidden')
	{
		$this->setStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 403;

		return null;
	}

	public function setNotFound($message = 'Not found')
	{
		$this->setStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 404;

		return null;
	}

	public function setSuccess($message = 'Success')
	{
		$this->setStatus('success');
		$this->setMessage($message);
		$this->httpStatus = 200;

		if (request()->isMethod('POST')) {
			$this->httpStatus = 201;
		}

		if (request()->isMethod('PUT')) {
			$this->httpStatus = 204;
		}

		return null;
	}

	public function setError(string $message = 'Error', string $queryError)
	{
		$this->setStatus('error');
		$this->setMessage($message);
		$this->httpStatus = 500;
		$this->setQueryError($queryError);

		return null;
	}

	public function setCustomError(string $message, int $errorCode, string $queryError = '')
	{
		$this->setStatus('error');
		$this->setMessage($message);
		$this->httpStatus = $errorCode;
		$this->setQueryError($queryError);

		return null;
	}

	public function returnResponse($data = true)
	{
		return ($this->status == 'success') ? $data : false;
	}
}