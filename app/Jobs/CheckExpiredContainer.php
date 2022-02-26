<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };

use App\Repositories\OrderRepository;
use App\Mail\Containers\{
    ContainerExpiredMail, 
    ContainerDeletedMail
};
use App\Jobs\SendMail;
use App\Models\{ Invoice, Container };

class CheckExpiredContainer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job execution timeout in seconds
     * 
     * @var int
     */
    public $timeout = 180;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = now()->copy();
        $updateContainers = Container::expired()->update(['status' => 'expired']);
        $expiredContainers = Container::expired()->get();

        foreach ($expiredContainers as $container) {
            // Set Expired when the time come
            if ($container->status != 'expired') {
                $container->status = 'expired';
                $container->save();
            }

            // New Order
            $order = $container->order;
            if (! $order->hasReOrder()) {
                $orderRepo = new OrderRepository;
                $orderRepo->reOrder($order);
            }
            $reOrder = $order->reOrder;

            // Count the expiration date and time limit
            $expirationDate = carbon()->parse($container->expiration_date);
            $timeLimit = $expirationDate->diffInDays($now);
            $limitLeft = 7 - $timeLimit;


            // Remind the customer
            $customerEmail = $order->customer->email;
            if ($limitLeft <= 3 || $limitLeft == 7) {

                // Remind user from email
                $email = new ContainerExpiredMail($container, $customerEmail);
                $sendReminderMail = new SendMail($email, $customerEmail);
                $sendReminderMail->delay($now->addSeconds(1));
                dispatch($sendReminderMail);
            }

            // Last day, limit is reached
            if ($limitLeft < 0) {
                // Send information to user email
                $email = new ContainerDeletedMail($container);
                $sendDeleteMail = new SendMail($email, $customerEmail);
                $sendDeleteMail->delay($now->addSeconds(1));
                dispatch($sendDeleteMail);

                // Delete container
                $container->delete();

                // Delete Reorder
                $reOrder->delete();
            }
        }
    }
}
