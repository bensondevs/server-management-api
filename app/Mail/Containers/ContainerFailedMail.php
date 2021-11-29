<?php

namespace App\Mail\Containers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Order;

class ContainerFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $suer, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subjet('Your container creation is failed.')
            ->from(env('MAIL_USERNAME'))
            ->view('mails.orders.containers.failed')
            ->with([
                'user' => $this->user,
                'order' => $this->order,
            ]);
    }
}
