<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Payments\FindPaymentRequest;
use App\Http\Requests\Payments\PopulatePaymentsRequest as PopulateRequest;

use App\Http\Resources\PaymentResource;

use App\Repositories\SebRepository;
use App\Repositories\PaymentRepository;

class PaymentController extends Controller
{
    protected $seb;
    protected $stripe;
    protected $paypal;

    protected $payment;

    public function __construct(SebRepository $sebRepository, PaymentRepository $paymentRepository)
    {
        $this->seb = $sebRepository;
    	$this->payment = $paymentRepository;
    }

    public function index(PopulateRequest $request)
    {
        if ($request->ajax()) {
            $options = $request->options();

            $payments = $this->payment->all($options, true);
            $payments = PaymentResource::apiCollection($payments);

            return response()->json(['payments' => $payments]);
        }

    	return view('dashboard.payments.index');
    }

    public function view(Payment $payment)
    {
        return view('dashboard.payments.view', compact('payment'));
    }

    public function load(Payment $payment)
    {
        $payment->card_informations = $payment->card_informations;
        $payment->payment_method = strtoupper($payment->payment_method);
        $payment->amount = format_money($payment->amount);

        return response()->json(['payment' => $payment]);
    }

    public function markAsPaid(MarkAsPaidRequest $request)
    {
        $payment = $request->getPayment();

        $this->payment->setModel($payment);
        $this->payment->markAsPaid();

        return apiResponse($this->payment);
    }
}
