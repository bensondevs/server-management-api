<?php

namespace App\Enums\Container\Nginx;

use BenSampo\Enum\Enum;

final class ContainerNginxEnability extends Enum
{
    /**
     * Container NGINX Start on Boot Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container NGINX Start on Boot Disabled
     */
    const Disabled = 2;
}
