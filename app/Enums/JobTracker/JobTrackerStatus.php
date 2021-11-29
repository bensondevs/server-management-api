<?php

namespace App\Enums\JobTracker;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class JobTrackerStatus extends Enum implements LocalizedEnum
{
    /**
     * Job execution is still waiting for response
     * 
     * @var int
     */
    const Waiting = 1;

    /**
     * Job execution is success
     * 
     * @var int
     */
    const Success = 2;

    /**
     * Job execution is failed
     * 
     * @var int
     */
    const Failed = 3;

    /**
     * Get badge HTML class for status <span> class
     * 
     * @return string
     */
    public function badgeHtmlClass()
    {
        switch ($this->value) {
            case self::Waiting:
                $labelType = 'label-secondary';
                break;
            case self::Success:
                $labelType = 'label-success';
                break;
            case self::Failed:
                $labelType = 'label-danger';
                break;
            
            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}