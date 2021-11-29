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
     * Order is already activated and 
     * service container should be running
     * 
     * @var int
     */
    const Activated = 2;

    /**
     * Order is expired and service container should be off
     * 
     * @var int
     */
    const Expired = 3;

    /**
     * Order is destoryed and service container should be destoryed
     * 
     * @var int
     */
    const Destroyed = 4;

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

            case self::Destroyed:
                
                $labelType = 'label-danger';
                
                break;
            
            default:
                
                $labelType = 'label-secondary';
                
                break;
        }

        return 'label ' . $labelType . ' label-inline font-weight-lighter mr-2';
    }
}
