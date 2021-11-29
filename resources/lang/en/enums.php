<?php

use App\Enums\Payment\PaymentMethod;

use App\Enums\JobTracker\JobTrackerStatus;

return [
    // Payment
    PaymentMethod::class => [
        PaymentMethod::SEB => 'SEB',
        PaymentMethod::Paypal => 'Paypal',
        PaymentMethod::Stripe => 'Stripe',
    ],

    // Job Tracker
    JobTrackerStatus::class => [
        JobTrackerStatus::Waiting => 'Waiting',
        JobTrackerStatus::Success => 'Success',
        JobTrackerStatus::Failed => 'Failed',
    ],
];