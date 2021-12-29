<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Subscriptions\{
    RenewMultipleSubscriptionsRequest as RenewMultipleRequest
};
use App\Http\Resources\SubscriptionResource;

use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;

class SubscriptionController extends Controller
{
    /**
     * Subscription repository class container
     * 
     * @var \App\Repositories\SubscriptionRepository
     */
    private $subs;

    /**
     * Controller constructor method
     * 
     * @param  \App\Repositories\SubscriptionRepository  $subs
     * @return void
     */
    public function __construct(SubscriptionRepository $subs)
    {
        $this->subs = $subs;
    }

    /**
     * Populate user subscriptions
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function subscriptions()
    {
        $subs = auth()->user()->subscriptions;
        $subs = SubscriptionResource::collection($subs);

        return response()->json(['subscriptions' => $subs]);
    }

    /**
     * Show subscription details
     * 
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Support\Facades\Response
     */
    public function show(Subscription $subscription)
    {
        $subs = new SubscriptionResource($subscription);

        return response()->json(['subscription' => $subs]);
    }

    /**
     * Create order to renew subscription
     * 
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Support\Facades\Response
     */
    public function renew(Subscription $subscription)
    {
        $this->subs->setModel($subscription);
        $this->subs->createRenewalOrder();
        
        return apiResponse($this->subs);
    }

    /**
     * Create order to renew multiple user subscriptions
     * 
     * @param  RenewMultipleRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function renewMultiple(RenewMultipleRequest $request)
    {
        $ids = $request->input('subscription_ids');
        $subscriptions = Subscription::whereIn('id', $ids)->get();
        $this->subs->createMultipleRenewalOrder($subscriptions);

        return apiResponse($this->subs);
    }

    /**
     * Report subscription
     * 
     * @param  ReportRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function report()
    {
        //
    }
}
