<?php

namespace App\Enums\PrecreatedContainer;

use BenSampo\Enum\Enum;

final class PrecreatedContainerStatus extends Enum
{
    /**
     * Precreated container is prepared with data
     * from user input and selections. 
     * This status will stay until the payment of order is settled
     * 
     * @static
     * @var int
     */
    const Prepared = 1;

    /**
     * The container has been created through this model
     * 
     * @static
     * @var int
     */
    const Created = 2;

    /**
     * The pre-created container is failed to be created
     * The reason can be various, most of them because selected 
     * server might be full or the selected server does not have free subnet
     * 
     * @static
     * @var int
     */
    const Waiting = 3;
}
