<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\Payment\{
    PaymentMethod as Method,
    PaymentStatus as Status
};

class PaymentController extends Controller
{
    public function paymentMethods()
    {
        $methods = PaymentMethod::asSelectArray();

        return response()->json(['methods' => $methods]);
    }

    public function paymentStatuses()
    {
        $statuses = PaymentStatus::asSelectArray();

        return response()->json(['statuses' => $statuses]);
    }

    public function statusBadges()
    {
        $statuses = collect(PaymentStatus::asSelectArray());

        return $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new PaymentStatus($value))->badgeHtmlClass(),
            ];
        });
    }
}