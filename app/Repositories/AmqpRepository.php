<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;
use Enqueue\AmqpExt\{ AmqpConnectionFactory, AmqpContext };
use App\Traits\RabbitMqRepositoryResponseHandler;
use App\Consumers\JsonConsumer;
use App\Models\{ Server, Setting };

class AmqpRepository extends BaseRepository
{
	use RabbitMqRepositoryResponseHandler;

	/**
	 * Configuration for RabbitMQ
	 * 
	 * @var array|null
	 */
	protected $configurations;
	
	/**
	 * Consumer class container
	 * 
	 * @var \App\Consumers\JsonConsumer|null
	 */
	protected $consumer;

	/**
	 * AMQP Connection Context container
	 * 
	 * @var \Enqueue\AmqpExt\AmqpContext|null
	 */
	protected $context;

	/**
	 * AMQP Queue Class container
	 * 
	 * @var \Interop\Amqp\Impl\AmqpQueue|null
	 */
	protected $queue;

	/**
	 * Request correlation ID
	 * 
	 * @return string
	 */
	protected $correlationId;

	/**
	 * Repository constructor method
	 * 
	 * @param  array  $settings
	 * @return void
	 */
	public function __construct(array $settings = [])
	{
		$this->configurations = $settings ?: Setting::rabbitMQSettings();
	}

	/**
	 * Set configuration of the connection factory
	 * 
	 * @param  string  $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function setConfiguration(string $key, $value)
	{
		$this->configurations[$key] = $value;
	}

	/**
	 * Set multiple configuration
	 * 
	 * @param  array  $configurations
	 * @return void
	 */
	public function setConfigurations(array $configurations)
	{
		$this->configurations = $configurations;
	}

	/**
	 * Execute connect using connection factory
	 * 
	 * @return Enqueue\AmqpExt\AmqpContext
	 */
	public function connect()
	{
		if ($this->context) return true;

		$configurations = $this->configurations;
		$connection = new AmqpConnectionFactory($configurations);
		return $this->context = $connection->createContext();
	}

	/**
	 * Create or select queue
	 * 
	 * @param  string  $queueName
	 * @return void
	 */
	public function createQueue(string $queueName)
	{
		$this->queue = $this->context->createQueue('Response.' . $queueName);
		$this->context->declareQueue($this->queue);

		$this->queue = $this->context->createQueue($queueName);
		$this->context->declareQueue($this->queue);
	}

	/**
	 * Connect to queue
	 * 
	 * @param  string  $queueName
	 * @return void
	 */
	public function connectQueue(string $queueName)
	{
		$this->connect();
		$this->createQueue($queueName);
	}

	/**
	 * Connect to server using server model
	 * 
	 * @param  \App\Models\Server  $server
	 * @return Enqueue\AmqpExt\AmqpQueue
	 */
	public function connectServerQueue(Server $server)
	{
		return $this->connectQueue($server->full_server_name);
	}

	/**
	 * Publish message as string
	 * 
	 * @param  string  $message
	 * @return void
	 */
	public function publish(string $message)
	{
		$_message = $this->context->createMessage($message);
		$this->context->createProducer()
			->send($this->queue, $_message);
	}

	/**
	 * Consume JSON
	 * 
	 * @param  string  $responseQueue
	 * @param  string  $uuid
	 * @return array
	 */
	public function consumeJson(string $responseQueue, string $uuid = '')
	{
		$consumer = new JsonConsumer($responseQueue);
		$consumer->setUuid($uuid ?: $this->correlationId);
		$consumer->handle();

		return $consumer->getResponse();
	}

	/**
	 * Consume server response after sending message
	 * 
	 * @param  \App\Models\Server  $server
	 * @param  string  $uuid
	 * @return array
	 */
	public function consumeServerResponse(Server $server, string $uuid = '')
	{
		$queue = 'Response.' . $server->full_server_name;
		return $this->consumeJson($queue, $uuid);
	}

	/**
	 * Publish array as JSON
	 * 
	 * @param  array  $data
	 * @return string
	 */
	public function publishJson(array $data)
	{
		if (! isset($data['uuid']))
			$data['uuid'] = generateUuid();
		
		$jsonString = json_encode($data);
		$this->publish($jsonString);
		return $this->correlationId = $data['uuid'];
	}

	/**
	 * Create queue and send message directly
	 * 
	 * @param  string  $queueName
	 * @param  string  $message
	 * @return void
	 */
	public function produce(string $queueName, string $message)
	{
		$this->createQueue($queueName);
		$this->publish($message);
	}

	/**
	 * Directly publish message to queue
	 * 
	 * @param  string  $queueName
	 * @param  string  $message
	 * @return void
	 */
	public function directPublish(string $queueName, string $message)
	{
		$this->connect();
		$this->createQueue($queueName);
		$this->publish($message);
	}
}