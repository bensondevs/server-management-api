<?php

namespace App\Enums\Pricing;

use BenSampo\Enum\Enum;

final class PricingStatus extends Enum
{
    /**
     * Pricing is up and showable to user
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Pricing is inactive and hidden from user
     * 
     * @var int
     */
    const Inactive = 2;
}