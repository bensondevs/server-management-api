<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

use App\Http\Requests\Payments\{
    CreatePaymentRequest as CreateRequest
};

use App\Repositories\Payments\PaymentRepository;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Payment Repository Class Container
     * 
     * @var \App\Repositories\PaymentRepository
     */
    private $payment;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\PaymentRepository  $payment
     * @return void
     */
    public function __construct(PaymentRepository $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Populate all user payment
     * 
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function payments(Request $request)
    {
        $payments = QueryBuilder::for(Payment::class)
            ->allowedFilters(Payment::ALLOWED_FILTERS)
            ->allowedSorts('created_at', 'amount', 'status', 'methods')
            ->paginate()
            ->appends($request->query());

        return response()->json(['payments' => $payments]);
    }

    /**
     * Create payment from the order
     * 
     * @param CreateRequest  $request
     * @param \App\Models\Order  $order
     * @return 
     */
    public function create(CreateRequest $request, Order $order)
    {
        $method = $request->input('method');
        $payment = $this->payment->create($order, $method);

        return apiResponse($this->payment, ['payment' => $payment]);
    }

    /**
     * Show payment resource
     * 
     * @param  \App\Models\Payment  $payment
     * @return Illuminate\Support\Facades\Response
     */
    public function show(Payment $payment)
    {
        $payment = new PaymentResource($payment);
        return response()->json(['payment' => $payment]);
    }

    /**
     * Report payment
     * 
     * @param  ReportPaymentRequest $request
     * @param  \App\Models\Payment  $payment
     * @return Illuminate\Support\Facades\Response
     */
    public function report(ReportPaymentRequest $request, Payment $payment)
    {
        $this->payment->setModel($payment);
        $this->payment->sendReport($request->validated());
        return apiResponse($this->payment);
    }
}