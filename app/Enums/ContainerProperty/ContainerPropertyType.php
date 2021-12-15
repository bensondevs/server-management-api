<?php

namespace App\Enums\ContainerProperty;

use BenSampo\Enum\Enum;

final class ContainerPropertyType extends Enum
{
    /**
     * The disk size of the container
     * 
     * @var int
     */
    const DiskSize = 1;

    /**
     * Disk array of the container
     * 
     * @var int
     */
    const DiskArray = 2;

    /**
     * Breakpoints of the container
     * 
     * @var int
     */
    const Breakpoints = 3;
}