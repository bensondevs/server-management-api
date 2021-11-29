<?php

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;

final class PaymentStatus extends Enum
{
    /**
     * Payment Status is unpaid
     * 
     * @return int
     */
    const Unpaid = 1;

    /**
     * Payment status is settled
     * 
     * @return int
     */
    const Settled = 2;

    /**
     * Payment status is failed
     * 
     * @return int
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
            case self::Unpaid:
                $labelType = 'label-secondary';
                break;
            
            case self::Settled:
                $labelType = 'label-success';
                break;

            case self::Failed:
                $labelType = 'label-failed';
                break;

            default:
                $labelType = 'label-secondary';
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}