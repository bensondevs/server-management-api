<?php

namespace App\Enums\Subscription;

use BenSampo\Enum\Enum;

final class SubscriptionStatus extends Enum
{
    /**
     * Subscription is active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Subscription is in grade period
     * 
     * @var int
     */
    const GracePeriod = 2;

    /**
     * Subscription is expired
     * 
     * @var int
     */
    const Expired = 3;
}
