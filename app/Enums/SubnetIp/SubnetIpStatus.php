<?php

namespace App\Enums\SubnetIp;

use BenSampo\Enum\Enum;

final class SubnetIpStatus extends Enum
{
    /**
     * Subnet IP is free to be used
     * 
     * @var int
     */
    const Free = 1;

    /**
     * Subnet IP is assigned to a certain user
     * 
     * @var int
     */
    const Assigned = 2;

    /**
     * Subnet IP is forbidden to be used
     * 
     * @var int
     */
    const Forbidden = 3;
}
