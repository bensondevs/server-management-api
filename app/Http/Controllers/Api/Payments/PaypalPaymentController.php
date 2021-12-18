<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaypalPaymentController extends Controller
{
    /**
     * Repository class container
     * 
     * @var \App\Repositories\Payments\PaypalRepository
     */
    private $paypal;

    /**
     * Controller constructor method
     * 
     * @param   \App\Repositories\Payments\PaypalRepository  $paypal
     * @return  void
     */
    public function __construct(PaymentRepository $paypal)
    {
        $this->paypal = $paypal;
    }
}
