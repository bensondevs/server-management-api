<?php

namespace App\Enums\Order;

use BenSampo\Enum\Enum;

final class OrderType extends Enum
{
    /**
     * Order type is new. Means this will create
     * new container after the payment is settled.
     * 
     * @var int
     */
    const New = 1;

    /**
     * Order type is renewal. Means that, this order
     * will add the active time of subscription after
     * the payment is settled.
     * 
     * @var int
     */
    const Renewal = 2;
}
