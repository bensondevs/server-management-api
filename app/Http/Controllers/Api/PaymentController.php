<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Payments\{ PayOrderRequest, PaymentCallbackRequest };

use App\Http\Resources\PaymentResource;

use App\Models\{ Order, Payment };
use App\Repositories\{ SebRepository, PaymentRepository };

class PaymentController extends Controller
{
    /**
     * SEB Repository Class Container
     * 
     * @var \App\Repositories\SebRepository
     */
    private $seb;

    /**
     * Payment Repository Class Container
     * 
     * @var \App\Repositories\PaymentRepository
     */
    private $payment;

    /**
     * Controller constructor methos
     * 
     * @param \App\Repositories\SebRepository  $seb
     * @param \App\Repositories\PaymentRepository  $payment
     * @return void
     */
    public function __construct(SebRepository $seb, PaymentRepository $payment)
    {
        $this->seb = $seb;
    	$this->payment = $payment;
    }

    /**
     * Populate user payments
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function payments()
    {
    	$payments = $this->payment->all();
        $payments = PaymentResource::collection($payments);
    	return response()->json(['payments' => $payments]);
    }

    public function payWithSeb(PayOrderRequest $request, Order $order)
    {
        /*
            Order Data
        */
        $order = $request->getOrder();

        if ($payment = $this->payment->hasBilledOrder($order)) {
            return apiResponse($this->payment, $payment);
        }

        /*
            Execute payment
        */
        $paymentData = [
            'user_id' => $request->user()->id,
            'order_id' => $order->id,
            'amount' => $order->countTotal(),
        ];

        $billingInput = $request->validated();

        if ($payment = $order->payment) {
            $this->payment->setModel($payment);
        }

        $payment = $this->payment->save($paymentData, $billingInput);

        return apiResponse($this->payment, $payment);
    }

    public function paymentCallback(PaymentCallbackRequest $request)
    {
        $payloads = $request->onlyInRules();
        $payment = $this->payment->recieveCallback($payloads);

        $clientDashboardUrl = env('CUSTOMER_DASHBOARD_URL', 'https://dash.cloud.lt/dashboard');
        $clientOrderPageUrl = $clientDashboardUrl . '/payment/view?id=' . $payment->id;

        return redirect()->away($clientOrderPageUrl);
    }

    public function checkPayment(CheckPaymentRequest $request)
    {
        $payment = $this->payment->find($request->input('id'));
        $payment = $this->payment->checkPayment();

        return apiResponse($this->payment, $payment);
    }

    public function view(Request $request)
    {
        $id = $request->get('id');
        $payment = $this->payment->find($id);
        $payment->load(['order', 'user', 'order.plan', 'order.plan.servicePlan']);
        $payment = new PaymentResource($payment);
        return response()->json(['payment' => $payment]);
    }
}
