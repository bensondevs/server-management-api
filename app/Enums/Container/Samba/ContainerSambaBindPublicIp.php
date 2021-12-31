<?php

namespace App\Enums\Container\Samba;

use BenSampo\Enum\Enum;

final class ContainerSambaBindPublicIp extends Enum
{
    /**
     * Samba bind to public ip still in requesting process
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Samba shared folders are binded to public ip
     * 
     * @var int
     */
    const Binded = 1;

    /**
     * Samba shared folders are not binded to public ip
     * 
     * @var int
     */
    const Unbinded = 2;

    /**
     * Samba shared folders bind status unknown
     * 
     * @var int
     */
    const Unknown = 3;
}
