<?php

namespace App\Enums\ServicePlan;

use BenSampo\Enum\Enum;

final class ServicePlanStatus extends Enum
{
    /**
     * Service plan is active and buyable
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Service plan is inactive and non-buyable
     * 
     * @var int
     */
    const Inactive = 2;

    /**
     * Service plan is suspended
     * 
     * @var int
     */
    const Suspended = 3;
}