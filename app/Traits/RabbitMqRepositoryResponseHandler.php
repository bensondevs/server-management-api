<?php

namespace App\Traits;

trait RabbitMqRepositoryResponseHandler 
{
    /**
     * RabbitMQ Response container
     * 
     * @var array
     */
    private $rabbitmqResponse;

    /**
     * Handle rabbit mq response
     * 
     * @param  mixed  $response
     * @return bool
     */
    public function handleRabbitMqResponse($response = null)
    {
        $this->rabbitmqResponse = $response;

        if (is_null($response)) {
            return $this->setCustomError('Received null response.', 510);
        }

        if (! is_array($response)) {
            return $this->setCustomError('Unprocessable response data.', 510);
        }

        $this->status = $response['status'];
        $this->message = isset($response['message']) ? $response['message'] : 'Success';

        return true;
    }

    /**
     * Return rabbit mq response
     * 
     * @return array
     */
    public function returnRabbitMqResponse()
    {
        if (! $this->rabbitmqResponse) return [];

        $extra = $this->rabbitmqResponse;
        if (isset($extra['status'])) unset($extra['status']);
        if (isset($extra['message'])) unset($extra['message']);
        // if (isset($extra['uuid'])) unset($extra['uuid']);
        
        return $extra;
    }
}