<?php

namespace App\Enums\ServicePlan;

use BenSampo\Enum\Enum;

final class ServicePlanTimeUnit extends Enum
{
    /**
     * Time unit will be counted with days
     * 
     * @var int
     */
    const Day = 1;

    /**
     * Time unit will be counted with month
     * and convertable to days
     * 
     * @var int
     */
    const Month = 2;

    /**
     * Time unit will be counted with year
     * and convertable to days and months
     * 
     * @var int
     */
    const Year = 3;
}