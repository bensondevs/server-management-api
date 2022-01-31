<?php

namespace App\Enums\Container\Nfs;

use BenSampo\Enum\Enum;

final class ContainerNfsBindPublicIp extends Enum
{
    /**
     * NFS bind to public ip still in requesting process
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * NFS exports are binded to public ip
     * 
     * @var int
     */
    const Binded = 1;

    /**
     * NFS exports are not binded to public ip
     * 
     * @var int
     */
    const Unbinded = 2;

    /**
     * NFS exports bind status is unknown
     * 
     * @var int
     */
    const Unknown = 3;
}
