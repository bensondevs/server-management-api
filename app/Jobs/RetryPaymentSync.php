<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Payment;
use App\Repositories\PaymentRepository;

class RetryPaymentSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job execution timeout in seconds
     * 
     * @var int
     */
    public $timeout = 900; // 15 mins max

    /**
     * Payment repository class container
     * 
     * @var \App\Repositories\PaymentRepository|null
     */
    private $paymentRepository;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Payment  $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->paymentRepository = new PaymentRepository;
        $this->paymentRepository->setModel($payment);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->paymentRepository->checkPayment();
    }
}
