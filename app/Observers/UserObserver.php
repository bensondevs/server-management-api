<?php

namespace App\Observers;

use App\Models\User;

use App\Mail\Users\RegisterConfirmationMail;

use App\Jobs\SendMail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        if (! $user->email_verified_at) {
            $verificationMail = new RegisterConfirmationMail($user);
            $send = new SendMail($verificationMail, $user->email);
            $send->delay(1);
            dispatch($send);
        }

        $executorUser = auth()->user(); 
        activity()
            ->performedOn($user)
            ->causedBy($executorUser)
            ->log($user->anchorName() . '\'s account has been created by ' . $executorUser->anchorName());
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $userAnchor = $user->anchorName();

        $executorUser = auth()->user();
        $executorAnchor = $executorUser->anchorName();

        if ($usernameUpdated = $user->isDirty('username')) {
            $message = $userAnchor . '\'s username has been updated by ' . $executorAnchor; 
            activity()->performedOn($user)->causedBy($executorUser)->log($message);
        }

        if ($emailUpdated = $user->isDirty('email')) {
            $message = $userAnchor . '\'s email has been updated by ' . $executorAnchor;
            activity()->performedOn($user)->causedBy($executorUser)->log($message);
        }

        if ($passwordUpdated = $user->isDirty('password')) {
            $message = $userAnchor . '\'s password has been updated by ' . $executorAnchor;
            activity()->performedOn($user)->causedBy($executorUser)->log($message);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $userAnchor = $user->anchorName();

        $executorUser = auth()->user();
        $executorAnchor = $executorUser->anchorName();

        $message = $userAnchor . '\'s has been deleted by ' . $executorAnchor;
        activity()->performedOn($user)->causedBy($executorUser)->log($message);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
