<?php

namespace App\Traits;

trait RabbitMqRepositoryResponseHandler 
{
    private $rabbitmqResponse;

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