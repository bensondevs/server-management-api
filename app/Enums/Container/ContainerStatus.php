<?php

namespace App\Enums\Container;

use BenSampo\Enum\Enum;

final class ContainerStatus extends Enum
{
    /**
     * Service container is currently inactive
     * 
     * @var int
     */
    const Inactive = 0;

    /**
     * Service container is currently running
     * 
     * @var int
     */
    const Running = 1;

    /**
     * Service container is currently mounted
     * 
     * @var int
     */
    const Mounted = 2;

    /**
     * Service container is currently stopped
     * 
     * @var int
     */
    const Stopped = 3;

    /**
     * Service container is currently unknown
     * 
     * This can be due to container is not yet created
     * at the server
     * 
     * @var int
     */
    const Unknown = 4;

    /**
     * Get badge HTML class for status <span> class
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case self::Stopped:
                $labelType = 'label-danger';
                break;
            
            case self::Running:
                $labelType = 'label-primary';
                break;

            case self::Mounted:
                $labelType = 'label-success';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}