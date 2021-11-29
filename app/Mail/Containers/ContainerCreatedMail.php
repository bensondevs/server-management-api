<?php

namespace App\Mail\Containers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\Order;
use App\Models\Container;

class ContainerCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $order;
    protected $container;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Order $order, 
        User $user,
        Container $container
    )
    {
        $this->user = $user;
        $this->order = $order;
        $this->container = $container;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Congratulation, your container has been created')
            ->from(env('MAIL_USERNAME'))
            ->view('mails.containers.created')
            ->with([
                'user' => $this->user,
                'order' => $this->order,
                'container' => $this->container,
            ]);
    }
}
