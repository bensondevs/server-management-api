<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Payments\Seb\{
    SebPaymentRequest as PaymentRequest,
    SebPaymentCallbackRequest as PaymentCallbackRequest
};
use App\Http\Resources\SebPaymentResource;
use App\Repositories\Payments\SebRepository;
use App\Models\SebPayment;

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
    public function __construct(SebRepository $sebPayment)
    {
        $this->sebPayment = $sebPayment;
    }

    /**
     * Pay with SEB Payment Gateway
     * 
     * @param PaymentRequest  $request
     * @param \App\Models\SebPayment  $seb
     * @return Illuminate\Support\Facades\Response
     */
    public function pay(PaymentRequest $request, SebPayment $seb)
    {
        $this->sebPayment->setModel($seb);
        $input = $request->validated();
        $seb = $this->sebPayment->payOnce($input);
        return apiResponse($this->sebPayment, ['seb_payment' => $seb]);
    }

    /**
     * Show payment details
     * 
     * @param \App\Models\SebPayment  $sebPayment
     * @return Illuminate\Support\Facades\Response
     */
    public function show(SebPayment $seb)
    {
        $seb->load(['apiResponses']);
        $seb = new SebPaymentResource($seb);
        return response()->json(['seb_payment' => $seb]);
    }

    /**
     * Handle payment callback request
     * 
     * @param PaymentCallbackRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function callback(PaymentCallbackRequest $request)
    {
        $callback = $request->validated();
        $this->sebPayment->handleCallback($callback);
        return apiResponse($this->sebPayment);
    }

    /**
     * Syncronise SEB Payment data
     * 
     * @param \App\Models\SebPayment  $seb
     * @return Illuminate\Support\Facades\Response
     */
    public function check(SebPayment $seb)
    {
        $this->sebPayment->setModel($seb);
        $seb = $this->sebPayment->checkPayment();
        return apiResponse($this->sebPayment, ['seb_payment' => $seb]);
    }
}
