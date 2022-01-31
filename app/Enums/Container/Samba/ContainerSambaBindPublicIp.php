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

            case self::Binded:
                $labelType = 'label-success';
                break;
            
            case self::Unbinded:
                $labelType = 'label-light';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
