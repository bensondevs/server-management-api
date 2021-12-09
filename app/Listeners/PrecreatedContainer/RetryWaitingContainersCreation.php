<?php

namespace App\Listeners\PrecreatedContainer;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RetryWaitingContainersCreation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Models\PrecreatedContainer  $event
     * @return void
     */
    public function handle(PrecreatedContainer $event)
    {
        //
    }
}
