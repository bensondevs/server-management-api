<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\{ ShouldBeUnique, ShouldQueue };
use Illuminate\Queue\{ InteractsWithQueue, SerializesModels };
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job timeout in seconds
     * 
     * @var int
     */
    public $timeout = 900; // 15 mins max

    /**
     * Mailable template container
     * 
     * @var \Illuminate\Mail\Mailable|null
     */
    private $mailable;

    /**
     * Recipient email address
     * 
     * @var string|null
     */
    private $recipient;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Mail\Mailable
     * @param  string  $recipient
     * @return void
     */
    public function __construct(Mailable $mailable, string $recipient)
    {
        $this->mailable = $mailable;
        $this->recipient = $recipient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send email
        Mail::to($this->recipient)->send($this->mailable);
    }
}
