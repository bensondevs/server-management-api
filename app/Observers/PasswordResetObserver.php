<?php

namespace App\Observers;

use App\Models\PasswordReset;
use App\Mail\Users\ForgotPasswordMail;

class PasswordResetObserver
{
    /**
     * Handle the PasswordReset "creating" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function creating(PasswordReset $reset)
    {
        $reset->token = random_string(10);
        $reset->created_at = now();
    }

    /**
     * Handle the PasswordReset "created" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function created(PasswordReset $reset)
    {
        $mailable = new ForgotPasswordMail($reset);
        send_mail($mailable, $reset->email);
    }

    /**
     * Handle the PasswordReset "updated" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function updated(PasswordReset $reset)
    {
        //
    }

    /**
     * Handle the PasswordReset "deleted" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function deleted(PasswordReset $reset)
    {
        //
    }

    /**
     * Handle the PasswordReset "restored" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function restored(PasswordReset $reset)
    {
        //
    }

    /**
     * Handle the PasswordReset "force deleted" event.
     *
     * @param  \App\Models\PasswordReset  $reset
     * @return void
     */
    public function forceDeleted(PasswordReset $reset)
    {
        //
    }
}
