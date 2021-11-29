<?php

namespace App\Enums\Payment;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class PaymentMethod extends Enum implements LocalizedEnum
{
    /**
     * Payment method using SEBank
     * 
     * @return int
     */
    const SEB = 1;

    /**
     * Payment method using Paypal
     * 
     * @return int
     */
    const Paypal = 2;

    /**
     * Payment method using Stripe
     * 
     * @return int
     */
    const Stripe = 3;
}
