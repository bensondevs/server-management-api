<?php

namespace App\Enums\Container\Vpn;

use BenSampo\Enum\Enum;

final class ContainerVpnEnability extends Enum
{
    /**
     * Container VPN Start On Boot Disabled
     * 
     * @var int
     */
    const Disabled = 0;

    /**
     * Container VPN Start On Boot Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container VPN is unknown
     * 
     * This can be due to the container is not yet created
     * at the server
     * 
     * @var int
     */
    const Unknown = 2;
}
