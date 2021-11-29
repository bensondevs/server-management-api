<?php

namespace App\Enums\Container\Samba;

use BenSampo\Enum\Enum;

final class ContainerSambaSmbdStatus extends Enum
{
    /**
     * Container Samba SMBD Status Requesting
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Container Samba SMBD Status Active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Container Samba SMBD Status Active
     * 
     * @var int
     */
    const Inactive = 2;

    /**
     * Container Samba SMBD Status Unknown
     * 
     * @var int
     */
    const Unknown = 3;

    /**
     * Get bootstrap badge HTML class
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case self::Requesting:
                $labelType = 'label-secondary';
                break;

            case self::Active:
                $labelType = 'label-success';
                break;
            
            case self::Inactive:
                $labelType = 'label-light';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}