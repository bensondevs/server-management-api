<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Payments\Seb\{
    SebPaymentRequest as PaymentRequest,
    SebPaymentCallbackRequest as PaymentCallbackRequest
};

class SebPaymentController extends Controller
{
    /**
     * SEB Repository Class Container
     * 
     * @var \App\Repositories\SebRepository
     */
    private $sebPayment;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\SebRepository  $seb
     * @return void
     */
    public function __construct(SebPaymentRepository $sebPayment)
    {
        $this->sebPayment = $sebPayment;
    }

    /**
     * Pay with SEB Payment Gateway
     * 
     * @param PaymentRequest  $request
     * @param \App\Models\Order  $order
     * @return Illuminate\Support\Facades\Response
     */
    public function pay(PaymentRequest $request, Order $order)
    {
        $input = $request->validated();
        $this->sebPayment->pay($order, $input);
        return apiResponse($this->sebPayment);
    }

    /**
     * Handle payment callback request
     * 
     * @param PaymentCallbackRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function callback(PaymentCallbackRequest $request)
    {
        $reference = $request->input('order_reference');
        $this->sebPayment->handleCallback($reference);
        return apiResponse($this->sebPayment);
    }

    /**
     * Syncronise SEB Payment data
     * 
     * @param \App\Models\SebPayment  $sebPayment
     * @return Illuminate\Support\Facades\Response
     */
    public function check(SebPayment $sebPayment)
    {
        $this->sebPayment->setModel($sebPayment);
        $this->sebPayment->check();
        return apiResponse($this->sebPayment);
    }

    /**
     * Show payment details
     * 
     * @param \App\Models\SebPayment  $sebPayment
     * @return Illuminate\Support\Facades\Response
     */
    public function show(SebPayment $sebPayment)
    {
        $sebPayment = new SebPaymentResource($sebPayment);
        return response()->json(['seb_payment' => $sebPayment]);
    }
}
