<?php

namespace App\Enums\Container\Nginx;

use BenSampo\Enum\Enum;

final class ContainerNginxStatus extends Enum
{
    /**
     * Container NGINX Status Requesting
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Container NGINX Status Active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Container NGINX Status Inactive
     * 
     * @var int
     */
    const Inactive = 2;

    /**
     * Container NGINX Status Unknown
     * 
     * @var int
     */
    const Unknown = 3;

    /**
     * Get bootstrap badge html class value
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case ContainerNginxStatus::Requesting:
                $labelType = 'label-secondary';
                break;

            case ContainerNginxStatus::Active:
                $labelType = 'label-success';
                break;
            
            case ContainerNginxStatus::Inactive:
                $labelType = 'label-light';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
