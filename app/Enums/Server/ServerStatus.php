<?php

namespace App\Enums\Server;

use BenSampo\Enum\Enum;

final class ServerStatus extends Enum
{
    /**
     * Server is active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Server is inactive
     * 
     * @var int
     */
    const Inactive = 2;
}
