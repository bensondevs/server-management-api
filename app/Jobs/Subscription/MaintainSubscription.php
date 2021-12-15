<?php

namespace App\Jobs\Subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Subscription;
use App\Enums\Subscription\SubscriptionStatus as Status;

class MaintainSubscription implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Maintain User's Subscription Job Class
    |--------------------------------------------------------------------------
    |
    | In this class, maintenance for each user's subscription will be handled.
    | This job class should be dispatched each day using cron job to keep the
    | subscription status of user syncronised and works well according to how
    | the system should work.
    |
    | This class will query all the subscription table and check the subscription
    | that's expired according to the time subscription is ended.
    |
    | Any logic to maintain subscription for user will be done inside this class's
    | method of "handle()".
    | 
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        foreach (Subscription::maintainable()->get() as $subscription) {
            switch (true) {
                case $subscription->isActive() and $subscription->shouldBeEndedNow():
                    $subscription->setIntoGracePeriod();
                    break;

                case $subscription->isInGracePeriod() and $subscription->shouldBeExpiredNow():
                    $subscription->setAsExpired();
                    break;

                case $subscription->isExpired() and $subscription->shouldBeTerminatedNow():
                    $subscription->terminate();
                    break;
                
                default:
                    // Do nothing...
                    break;
            }
        }
    }
}