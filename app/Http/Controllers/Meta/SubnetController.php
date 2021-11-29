<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\Subnet\SubnetStatus;

class SubnetController extends Controller
{
    /**
     * Collect all status badges for subnet
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statusBadges()
    {
        $statuses = collect(SubnetStatus::asSelectArray());
        return $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new SubnetStatus($value))->badgeHtmlClass(),
            ];
        });
    }
}