<?php

return [
	'default' => [
		'host' => env('RABBITMQ_HOST', '127.0.0.1'),
		'port' => env('RABBITMQ_PORT', 5672),
		'user' => env('RABBITMQ_USER', 'guest'),
		'pass' => env('RABBITMQ_PASSWORD', 'guest'),
		'vhost' => env('RABBITMQ_VHOST', '/'),
		'persisted' => false,
	],
];