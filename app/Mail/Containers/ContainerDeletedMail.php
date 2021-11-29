<?php

namespace App\Mail\Containers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Container;

class ContainerDeletedMail extends Mailable
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
        return $this->subject('Your container has been destroyed!')
            ->from(env('MAIL_USERNAME'))
            ->view('dashboard.mails.containers.deleted')
            ->with(['container' => $this->container]);
    }
}
