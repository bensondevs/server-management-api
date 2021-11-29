<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use Enqueue\AmqpExt\AmqpConnectionFactory;
use Enqueue\AmqpExt\AmqpContext;

use App\Traits\RabbitMqRepositoryResponseHandler;

use App\Consumers\JsonConsumer;

use App\Repositories\Base\BaseRepository;

use App\Models\Server;
use App\Models\Setting;

class AmqpRepository extends BaseRepository
{
	use RabbitMqRepositoryResponseHandler;

	protected $configurations;
	
	protected $consumer;
	protected $context;
	protected $queue;

	protected $correlationId;

	public function __construct(array $settings = [])
	{
		$this->configurations = $settings ?: Setting::rabbitMQSettings();
	}

	public function setConfiguration($key, $value)
	{
		return $this->configurations[$key] = $value;
	}

	public function setConfigurations(array $configurations)
	{
		return $this->configurations = $configurations;
	}

	public function connect()
	{
		if ($this->context) return true;

		$configurations = $this->configurations;
		$connection = new AmqpConnectionFactory($configurations);
		return $this->context = $connection->createContext();
	}

	public function createQueue(string $queueName)
	{
		$this->queue = $this->context->createQueue('Response.' . $queueName);
		$this->context->declareQueue($this->queue);

		$this->queue = $this->context->createQueue($queueName);
		$this->context->declareQueue($this->queue);
	}

	public function connectQueue(string $queueName)
	{
		$this->connect();
		$this->createQueue($queueName);
	}

	public function connectServerQueue(Server $server)
	{
		return $this->connectQueue($server->complete_server_name);
	}

	public function publish(string $message)
	{
		$_message = $this->context->createMessage($message);
		$this->context->createProducer()
			->send($this->queue, $_message);
	}

	public function consumeJson(string $responseQueue, string $uuid = '')
	{
		$consumer = new JsonConsumer($responseQueue);
		$consumer->setUuid($uuid ?: $this->correlationId);
		$consumer->handle();

		return $consumer->getResponse();
	}

	public function consumeServerResponse(Server $server, string $uuid = '')
	{
		$queue = 'Response.' . $server->complete_server_name;
		return $this->consumeJson($queue, $uuid);
	}

	public function publishJson(array $data)
	{
		if (! isset($data['uuid']))
			$data['uuid'] = generateUuid();
		
		$jsonString = json_encode($data);
		$this->publish($jsonString);
		return $this->correlationId = $data['uuid'];
	}

	public function produce(string $queueName, string $message)
	{
		$this->createQueue($queueName);
		$this->publish($message);
	}

	public function directPublish(string $queueName, string $message)
	{
		$this->connect();
		$this->createQueue($queueName);
		$this->publish($message);
	}
}