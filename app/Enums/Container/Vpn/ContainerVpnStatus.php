<?php

namespace App\Enums\Container\Vpn;

use BenSampo\Enum\Enum;

final class ContainerVpnStatus extends Enum
{
    /**
     * Status Request
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Status Active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Status Inactive
     * 
     * @var int
     */
    const Inactive = 2;

    /**
     * Status Unknown
     * 
     * @var int
     */
    const Unknown = 3;

    /**
     * Get Badge HTML Class for frontend bootstrap
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case ContainerVpnStatus::Requesting:
                $labelType = 'label-secondary';
                break;

            case ContainerVpnStatus::Active:
                $labelType = 'label-success';
                break;
            
            case ContainerVpnStatus::Inactive:
                $labelType = 'label-light';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
