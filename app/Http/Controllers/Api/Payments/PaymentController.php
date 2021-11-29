<?php

namespace App\Http\Controllers\Api\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Repositories\PaymentRepository;
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
     * Set recurring payment
     * 
     * @param 
     * @return
     */
    public function setRecurring()
    {
        //
    }

    /**
     * Stop recurring payment
     * 
     * @param 
     * @return
     */
    public function stopRecurring()
    {
        //
    }
}