<?php

namespace App\Enums\Container;

use BenSampo\Enum\Enum;

final class ContainerProperty extends Enum
{
    /**
     * The disk size of the container
     * 
     * @var int
     */
    const DiskSize = 1;

    /**
     * Operating system of the container
     * 
     * @var int
     */
    const OperatingSystem = 2;

    /**
     * Duration of the container validity
     * 
     * @var int
     */
    const Duration = 3;
}