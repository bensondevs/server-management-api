<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Currency extends Enum
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
