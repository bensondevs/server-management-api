<?php

namespace App\Enums\Container\Samba;

use BenSampo\Enum\Enum;

final class ContainerSambaSmbdEnability extends Enum
{
    /**
     * Container Samba SMBD Service Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container Samba SMBD Service Disabled
     * 
     * @var int
     */
    const Disabled = 2;
}
