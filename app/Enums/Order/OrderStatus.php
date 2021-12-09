<?php

namespace App\Enums\Order;

use BenSampo\Enum\Enum;

final class OrderStatus extends Enum
{
    /**
     * Order is created but unpaid yet
     * 
     * @var int
     */
    const Unpaid = 0;

    /**
     * Order is paid but not yet activated
     * 
     * @var int
     */
    const Paid = 1;

    /**
     * Order is expired and order is cancelled
     * 
     * @var int
     */
    const Expired = 2;

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

            case self::Paid:
                
                $labelType = 'label-primary';
                
                break;

            case self::Activated:
                
                $labelType = 'label-success';
                
                break;

            case self::Expired:
                
                $labelType = 'label-warning';
                
                break;
            
            default:
                
                $labelType = 'label-secondary';
                
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
