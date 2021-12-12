<?php

namespace App\Enums\Container;

use BenSampo\Enum\Enum;

final class ContainerOnServerStatus extends Enum
{
    /**
     * Container is not yet created on server
     * 
     * @var int
     */
    const Uncreated = 0;

    /**
     * Container is created on server
     * 
     * @var int
     */
    const Created = 1;

    /**
     * Container is active on server
     * 
     * @var int
     */
    const Active = 2;

    /**
     * Container is turned off on server
     * 
     * @var int
     */
    const Inactive = 3;

    /**
     * Container is destroyed on server
     * 
     * @var int
     */
    const Destroyed = 4;
}
