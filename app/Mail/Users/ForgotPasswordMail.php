<?php

namespace App\Mail\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\PasswordReset;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Reset token model class container
     * 
     * @var \App\Models\PasswordReset
     */
    private $passReset;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\PasswordReset  $passReset
     * @return void
     */
    public function __construct(PasswordReset $passReset)
    {
        $this->passReset = $passReset;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $passReset = $this->passReset;
        $token = $passReset->token;

        return $this
            ->from('support@diskray.lt', 'Support Diskray')
            ->subject('Diskray Password Reset Token')
            ->view('mails.users.forgot-password')
            ->with(['token' => $token]);
    }
}
