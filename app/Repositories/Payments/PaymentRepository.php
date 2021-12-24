<?php

namespace App\Repositories\Payments;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Order, Payment };
use App\Enums\Payment\PaymentStatus;
use App\Jobs\{ SendMail, RetryPaymentSync };
use App\Mail\Orders\OrderPlacedMail;

class PaymentRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new Payment);
	}

	/**
	 * Create payment from order
	 * 
	 * @param \App\Models\Order  $order
	 * @param int  $method
	 * @return \App\Models\Payment
	 */
	public function create(Order $order, int $method = 1)
	{
		try {
			$payment = $this->getModel();
			$payment->user_id = $order->user_id;
			$payment->order_id = $order->id;
			$payment->method = $method;
			$payment->currency = $order->currency;
			$payment->amount = $order->grand_total;
			$payment->save();

			$this->setModel($payment);

			$this->setSuccess('Successfully create payment for order.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to create payment.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Set status for the payment
	 * 
	 * @param int  $status
	 */
	public function setStatus(int $status = 1)
	{
		try {
			$payment = $this->getModel();
			$payment->status = $status;
			$payment->save();

			$this->setModel($payment);

			$this->setSuccess('Successfully set new status for payment.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to set status to payment.', $error);
		}
	}
}
