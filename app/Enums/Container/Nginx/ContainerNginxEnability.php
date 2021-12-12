<?php

namespace App\Enums\Container\Nginx;

use BenSampo\Enum\Enum;

final class ContainerNginxEnability extends Enum
{
    /**
     * Container NGINX Enability to start on boot is Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container NGINX Enability to start on boot is Disabled
     * 
     * @var int
     */
    const Disabled = 2;

    /**
     * Container NGINX Enability to start on boot is unknown
     * 
     * This can be due to the container is not yet created 
     * at the server
     * 
     * @var int
     */
    const Unknown = 3;
}
