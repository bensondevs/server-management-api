<?php

namespace App\Enums\Container\Samba;

use BenSampo\Enum\Enum;

final class ContainerSambaSmbdEnability extends Enum
{
    /**
     * Container Samba SMBD is requesting
     * 
     * @var int
     */
    const Requesting = 0;

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

    /**
     * Container Samba SMBD is unknown
     * 
     * This can be due to the container is not yet created
     * at the server
     * 
     * @var  int
     */
    const Unknown = 3;
}
