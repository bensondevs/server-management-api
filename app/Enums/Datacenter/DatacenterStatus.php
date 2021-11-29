<?php

namespace App\Enums\Datacenter;

use BenSampo\Enum\Enum;

final class DatacenterStatus extends Enum
{
    /**
     * Datacenter status of inactive
     * 
     * @var int
     */
    const Inactive = 0;

    /**
     * Datacenter status of active
     * 
     * @var int
     */
    const Active = 1;

    /**
     * Get badge HTML class for status <span> class
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        if ($this->value !== self::Active) {
            return 'label label-secondary label-inline font-weight-lighter mr-2';
        }

        return 'label label-success label-inline font-weight-lighter mr-2';
    }
}