<?php

namespace App\Enums\User;

use BenSampo\Enum\Enum;

final class UserAccountType extends Enum
{
    /**
     * User account is personal type
     * 
     * @var int
     */
    const Personal = 1;

    /**
     * User account is business type
     * 
     * @var int
     */
    const Business = 2;
}
