<?php

namespace App\Consumers;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class JsonConsumer 
{
	/**
	 * Acquired response from the rabbit mq broker
	 * This variable will contain response as JSON string
	 * 
	 * @var string|null
	 */
	private $response;

	/**
	 * Queue that should be listened 
	 * in order to get response
	 * 
	 * @var string|null
	 */
	private $responseQueue;

	/**
	 * UUID of request
	 * 
	 * @var string|null
	 */
	private $uuid;

	/**
	 * Consumer constructor method. 
	 * This will set which queue will be listened and
	 * get only certain uuid requested in order to pick right response
	 * 
	 * @param string|null  $queue
	 * @param string|null  $uuid
	 * @return void
	 */
	public function __construct(string $queue = '', string $uuid = '')
	{
		$this->responseQueue = $queue;
		$this->uuid = $uuid;
	}

	/**
	 * Set response queue to the class variable
	 * 
	 * @param string  $queue
	 * @return void
	 */
	public function setResponseQueue(string $queue)
	{
		$this->responseQueue = $queue;
	}

	/**
	 * Set response acquired from the rabbitmq broker
	 * 
	 * @param string  $response
	 * @return void
	 */
	public function setResponse(string $response)
	{
		$this->response = $response;
	}

	/**
	 * Set uuid of the request
	 * This uuid will be used to pick the correct response
	 * 
	 * @param string  $uuid
	 * @return void
	 */
	public function setUuid(string $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * Get response as decoded json array
	 * 
	 * @return array|null
	 */
	public function getResponse()
	{
		return json_decode($this->response, true);
	}

	/**
	 * Handle the consuming process
	 * 
	 * @param string  $uuid
	 * @return array|null
	 */
	public function handle(string $uuid = '')
	{
		// If no uuid is supplied in parameter
		// Get from the class variable instead
		if (! $uuid) {
			$uuid = $this->uuid;
		}
		$this->setUuid($uuid);
		
		[$host, $port, $user, $pass] = array_values(config('rabbitmq.default'));

		$connection = new AMQPStreamConnection($host, $port, $user, $pass);
		$channel = $connection->channel();

		$queue = $this->responseQueue;
		$channel->queue_declare($queue, true, false, false, false);
		
		$callback = function ($message) use ($uuid) { 
			$response = json_decode($message->body, true);
			if (isset($response['uuid'])) {
				if ($response['uuid'] == $uuid) {
					$this->setResponse($message->body); 
				}
			}
		};
		$channel->basic_consume($queue, '', false, true, false, false, $callback);

		while ($channel->is_consuming()) {
		    $channel->wait();

		    // Keep consuming if the response is not yet acquired
		    if ($this->response) {
		    	$channel->close();
		    	$connection->close();

		    	break;
		    }
		}

		return $this->getResponse();
	}
}