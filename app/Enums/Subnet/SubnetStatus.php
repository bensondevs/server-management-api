<?php

namespace App\Enums\Subnet;

use BenSampo\Enum\Enum;

final class SubnetStatus extends Enum
{
    /**
     * Subnet status inactive
     * 
     * @var int
     */
    const Unavailable = 0;

    /**
     * Subnet status active
     * 
     * @var int
     */
    const Available = 1;

    /**
     * Subnet status is forbidden to be used
     * 
     * @var int
     */
    const Forbidden = 2;

    /**
     * Get badge HTML class for status <span> class
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case self::Unavailable:
                $labelType = 'label-secondary';
                break;

            case self::Available:
                $labelType = 'label-success';
                break;

            case self::Forbidden:
                $labelType = 'label-danger';
                break;
            
            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}