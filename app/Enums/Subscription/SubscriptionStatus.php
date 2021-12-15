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
     * Subscription is in grade period.
     * 
     * This status means that the subscription is due,
     * but we still give time to user to pay so that
     * any data will not be destroyed.
     * 
     * @var int
     */
    const GracePeriod = 2;

    /**
     * Subscription is expired.
     * 
     * This means that the subscription is expired.
     * This can be the last call for the user to pay the subscription
     * before it will be completely destroyed.
     * 
     * @var int
     */
    const Expired = 3;

    /**
     * Subscription is terminated
     * 
     * @var int
     */
    const Terminated = 4;
}
