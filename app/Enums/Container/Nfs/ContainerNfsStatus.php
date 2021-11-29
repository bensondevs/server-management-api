<?php

namespace App\Enums\Container\Nfs;

use BenSampo\Enum\Enum;

final class ContainerNfsStatus extends Enum
{
    /**
     * Container NFS Service Status Requesting
     * 
     * @var int
     */
    const Requesting = 0;

    /**
     * Container NFS Service Status Active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Container NFS Service Status Inactive
     * 
     * @var int
     */
    const Inactive = 2;

    /**
     * Container NFS Service Status Unknown
     * 
     * @var int
     */
    const Unknown = 3;

    /**
     * Container NFS Service Status Failed
     * 
     * @var int
     */
    const Failed = 4;

    /**
     * Get bootstrap badge html class value
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case ContainerNfsStatus::Requesting:
                $labelType = 'label-secondary';
                break;

            case ContainerNfsStatus::Active:
                $labelType = 'label-success';
                break;
            
            case ContainerNfsStatus::Inactive:
                $labelType = 'label-light';
                break;
            case ContainerNfsStatus::Failed:
                $labelType = 'label-danger';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
