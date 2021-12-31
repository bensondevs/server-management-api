<?php

namespace App\Enums\Container\Samba;

use BenSampo\Enum\Enum;

final class ContainerSambaNmbdEnability extends Enum
{
    /**
     * Container Samba NMBD is requesting
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Container Samba NMBD Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container Samba NMBD Disabled
     * 
     * @var int
     */
    const Disabled = 2;

    /**
     * Container Samba NMBD is unknown
     * 
     * This can be due to the container is not yet created
     * at the server
     * 
     * @var  int
     */
    const Unknown = 3;
}
