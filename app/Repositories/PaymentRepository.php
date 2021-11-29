<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ Order, Payment };
use App\Enums\Payment\PaymentStatus;
use App\Jobs\{ SendMail, RetryPaymentSync };
use App\Mail\Orders\OrderPlacedMail;

class PaymentRepository extends BaseRepository
{
	protected $seb;

	public function __construct()
	{
		$this->setInitModel(new Payment);
		$this->seb = new SebRepository();
	}

	public function allWithData()
	{
		$payments = Payment::with(['user'])->get();
		$this->setCollection($payments);

		return $this->getCollection();
	}

	public function save(array $paymentData, array $billingData = [])
	{
		try {
			// Initiate API Request
			$payloads = [
				'api_username' => env('SEB_USERNAME'),
	            'account_name' => env('SEB_ACCOUNT_NAME'),
	            'amount' => $paymentData['amount'],
	            'order_reference' => $paymentData['order_id'],
	            'token_agreement' => 'unscheduled',
	            'nonce' => str_replace('-', '', generateUuid()),
	            'timestamp' => carbon()->now(),
	            'email' => auth()->user()->email,
	            'customer_ip' => (string) request()->ip(),
	            'customer_url' => url('/api/callbacks/payment_callback'),
	            'preferred_country' => 'LT',
	            'billing_city' => $billingData['billing_city'],
	            'billing_country' => $billingData['billing_country'],
	            'billing_line1' => $billingData['billing_line1'],
	            'billing_line2' => isset($billingData['billing_line2']) ? $billingData['billing_line2'] : null,
	            'billing_line3' => isset($billingData['billing_line3']) ? $billingData['billing_line3'] : null,
	            'billing_postcode' => $billingData['billing_postcode'],
	            'billing_state' => $billingData['billing_state'],
	            'locale' => 'en',
	            'request_token' => true,
	            'skin_name' => 'shop1',
			];
			$response = $this->seb->payOnce($payloads);

			// Create Payment
			$payment = $this->getModel();
			$payment->fill($paymentData);
			$payment->billing_address = $billingData;
			$payment->vendor_api_response = $response;
			$payment->save();

			$this->setModel($payment);

			$this->setSuccess('Successfully save payment data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save payment.', $error);
		}

		return $response;
	}

	public function find($id)
	{
		return $this->getModel()->with(['user', 'order'])->findOrFail($id);
	}

	public function hasBilledOrder(Order $order)
	{
		if ($payment = Payment::findOrder($order)) {
			$payment->syncPaymentState();
			if ($payment->status != PaymentStatus::Failed && $payment->vendor_api_response) {
				$this->setSuccess('This order has been billed, not processing, just returning the payment note.');
				return $payment;
			}
		}

		return;
	}

	public function checkPayment()
	{
		try {
			$payment = $this->getModel();

			$reference = $payment->payment_reference;
			$response = $this->seb->checkPayment($reference);

			if (isset($response['error'])) {
				// Retry if failed.
				$retrySyncJob = new RetryPaymentSync($payment);
				$retrySyncJob->delay(carbon()->now()->addSeconds(1));
				dispatch($retrySyncJob);

				abort(500, 'Failed to load payment data from API, please try again.');
			}

			$payment->vendor_api_response = $response;
			$payment->syncPaymentState(); // include save

			$this->setModel($payment);

			$this->setSuccess('Successfully updating the payment information.');
		} catch (QueryException $qe) {
			$this->setError('Failed to update the payment ');
		}

		return $this->getModel();
	}

	public function markAsPaid()
	{
		try {
			$payment = $this->getModel();
			$payment->markAsPaid();

			$this->setModel($payment);

			$this->setSuccess('Successfully mark payment as paid.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed yo mark payment as paid.', $error);
		}

		return $this->getModel();
	}

	public function recieveCallback(array $payloads)
	{
		try {
			$order = Order::findOrFail($payloads['order_reference']);
			$payment = $order->payment;
			$payment->payment_reference = $payloads['payment_reference'];
			$payment->save(); 

			$this->setModel($payment);
			$this->checkPayment();

			$this->setSuccess('Successfully recieve callback.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to recieve callback.', $error);
		}

		return $this->getModel();
	}
}
