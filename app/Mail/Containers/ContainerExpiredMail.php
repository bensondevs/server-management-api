<?php

namespace App\Mail\Containers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;

class ContainerExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    private $container;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your container is expired!')
            ->from(env('MAIL_USERNAME'))
            ->view('mails.orders.containers.expired')
            ->with(['container' => $this->container]);
    }
}
