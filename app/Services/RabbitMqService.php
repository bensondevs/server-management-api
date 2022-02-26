<?php

namespace App\Services;

use Enqueue\AmqpExt\{ AmqpConnectionFactory, AmqpContext };
use App\Traits\RabbitMqRepositoryResponseHandler;
use App\Consumers\JsonConsumer;
use App\Models\{ Server, Setting };

class RabbitMqService
{
	use RabbitMqRepositoryResponseHandler;

	/**
	 * Configuration for RabbitMQ
	 * 
	 * @var array|null
	 */
	protected $configs;

	/**
	 * Consumer class container
	 * 
	 * @var  \App\Consumers\JsonConsumer|null
	 */
	protected $consumer;

	/**
	 * AMQP Connection context container
	 * 
	 * @var  \Enqueue\AmqpExt\AmqpContext|null
	 */
	protected $context;

	/**
	 * AMQP Queue class container
	 * 
	 * @var  \Interop\Amqp\Impl\AmqpQueue|null
	 */
	protected $queue;

	/**
	 * Request correlated ID
	 * 
	 * @var string
	 */
	protected $requestId;

	/**
	 * Service constructor method
	 * 
	 * @param  array  $settings
	 * @return void
	 */
	public function __construct(array $settings = [])
	{
		$this->configs = empty($settings) ?
			$settings :
			Setting::rabbitMQSettings();
	}

	/**
	 * Set config of the RabbitMQ service
	 * 
	 * This will nullify the context class container.
	 * 
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return $this
	 */
	public function setConfig(string $key, $value)
	{
		// Set the configuration value
		$this->configs[$key] = $value;

		// Nullify the context class
		$this->context = null;

		return $this;
	}

	/**
	 * Replace configs array with new array of configs.
	 * 
	 * This will nullify the context class container.
	 * 
	 * @param  array  $configs
	 * @return $this
	 */
	public function setConfigs(array $configs)
	{
		// Set the configuration value
		$this->configs = $configs;

		// Nullify the context class
		$this->context = null;

		return $this;
	}

	/**
	 * Make connection factory with AMQP server.
	 * 
	 * Connection made will create connection context
	 * and will be contained in the class attribute of
	 * `$this->context`. If the context has already has
	 * value, then this will do nothing and return
	 * the class instance instead.
	 * 
	 * @return this
	 */
	public function connect()
	{
		if ($this->context) return $this;

		$configs = $this->configs;
		$connection = new AmqpConnectionFactory($configs);

		// Set value to context when connection created
		$this->context = $connection->createContext();

		return $this;
	}

	/**
	 * Create queue in AMQP server. If the queue with
	 * the same name existing in the AMQP server, then
	 * this will select that queue instead.
	 * 
	 * @param  string  $queueName
	 * @return $this
	 */
	public function createQueue(string $queueName)
	{
		// Create or select response queue first
		$this->queue = $this->context->createQueue('Response.' . $queueName);
		$this->context->declareQueue($this->queue);

		// Create or select queue to send the message
		$this->queue = $this->context->createQueue($queueName);
		$this->context->declareQueue($this->queue);

		return $this;
	}

	/**
	 * Connect local producer to the queue
	 * 
	 * @param  string  $queueName
	 * @return $this
	 */
	public function connectQueue(string $queueName)
	{
		$this->connect();
		$this->createQueue($queueName);

		return $this;
	}

	/**
	 * Connect to server by calling full name of the server
	 * 
	 * @param  \App\Models\Server|null  $server
	 * @return $this
	 */
	public function connectServerQueue(Server $server)
	{
		// Connect to server in AMQP server
		$fullServerName = $server->full_server_name;
		$this->connectQueue($fullServerName);

		return $this;
	}

	/**
	 * Publish message as string to the connected AMQP.
	 * 
	 * Before executing this method. Make sure to make this
	 * service class connected to the AMQP server.
	 * 
	 * This class assume that AmqpContext has been created. 
	 * 
	 * @param  string  $message
	 * @return $this
	 */
	public function publish(string $message)
	{
		// Create AMQP Message instance to be sent
		$amqpMessage = $this->context->createMessage($message);

		// Create producer as the deliverer of the AMQP message
		$producer = $this->context->createProducer();
		$producer->send($this->queue, $amqpMessage); // Send the message

		return $this;
	}

	/**
	 * Consume JSON from the RabbitMQ server.
	 * 
	 * @param   string  $resQue
	 * @param   string  $uuid
	 * @return  array
	 */
	public function consumeJson(string $resQue, string $uuid = '')
	{
		// Create consumer instance
		$consumer = new JsonConsumer($resQue);

		// Set UUID to pick which request to be handled
		// Each request will be tagges with UUID
		// So the matched UUID only will be handled
		$consumer->setUuid($uuid ?: $this->correlationId);

		// Execute handle consuming
		$consumer->handle();

		// Get response from the handled consumer
		$response = $consumer->getResponse();

		return $response;
	}

	/**
	 * Do Consume JSON to certain server/queue.
	 * 
	 * This usually being called right after sending message.
	 * 
	 * @param  \App\Models\Server  $server
	 * @param  string              $uuid
	 * @return array
	 */
	public function consumeServerResponse(Server $server, string $uuid = '')
	{
		$queue = $server->full_server_name;
		return $this->consumeJson($queue, $uuid);
	}
}