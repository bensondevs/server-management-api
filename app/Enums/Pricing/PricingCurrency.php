<?php

namespace App\Enums\Pricing;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PricingCurrency extends Enum implements LocalizedEnum
{
    /**
     * Pricing if the user is from europe
     * 
     * @var int
     */
    const EUR = 1;

    /**
     * Pricing if the user is from non-europe country
     * 
     * @var int
     */
    const USD = 2;
}
