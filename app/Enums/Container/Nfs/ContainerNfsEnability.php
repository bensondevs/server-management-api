<?php

namespace App\Enums\Container\Nfs;

use BenSampo\Enum\Enum;

final class ContainerNfsEnability extends Enum
{
    /**
     * Container NFS Service Status Enabled
     * 
     * @var int
     */
    const Enabled = 1;

    /**
     * Container NFS Service Status Disabled
     * 
     * @var int
     */
    const Disabled = 2;

    /**
     * Container NFS Enability to start on boot is unknown
     * 
     * This can be due to the container is not yet created 
     * at the server
     * 
     * @var int
     */
    const Unknown = 3;
}
