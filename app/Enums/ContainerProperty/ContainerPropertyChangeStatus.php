<?php

namespace App\Enums\ContainerProperty;

use BenSampo\Enum\Enum;

final class ContainerPropertyChangeStatus extends Enum
{
    /**
     * Container property is not yet applied to server
     * 
     * @var int
     */
    const Uncreated = 0;

    /**
     * Container property is synched with server
     * 
     * @var int
     */
    const Synced = 1;

    /**
     * Container property is changed, but not synched
     * 
     * @var int
     */
    const Changed = 2;

    /**
     * Container property update is failed
     * 
     * @var int
     */
    const UpdateFailed = 3;
}
