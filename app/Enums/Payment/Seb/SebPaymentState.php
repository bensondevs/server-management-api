<?php

namespace App\Enums\Payment\Seb;

use BenSampo\Enum\Enum;

final class SebPaymentState extends Enum
{
    /**
     * Payment is in initial state
     * 
     * @var int
     */
    const Initial = 1;

    /**
     * Payment is waiting for SCA
     * 
     * @var int
     */
    const WaitingForSca = 2;

    /**
     * Payment is sent for processing
     * 
     * @var int
     */
    const SentForProcessing = 3;

    /**
     * Payment is waiting for 3DS Response
     * 
     * @var int
     */
    const WaitingFor3DsResponse = 4;

    /**
     * Payment is settled
     * 
     * @var int
     */
    const Settled = 5;

    /**
     * Payment is failed
     * 
     * @var int
     */
    const Failed = 6;

    /**
     * Payment is abandoned
     * 
     * @var int
     */
    const Abandoned = 7;

    /**
     * Payment has been voided due to rejection
     * from the vendor
     * 
     * @var int
     */
    const Voided = 8;

    /**
     * Payment has been refunded
     * 
     * @var int
     */
    const Refunded = 9;

    /**
     * Payment has been charged back to the client
     * 
     * @var int
     */
    const Chargebacked = 10;
}