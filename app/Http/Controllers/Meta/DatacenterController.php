<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Enums\Datacenter\DatacenterStatus;

class DatacenterController extends Controller
{
    /**
     * Collect all datacenter status badge classes
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function statusBadges()
    {
        $statuses = collect(DatacenterStatus::asSelectArray());
        return $statuses->map(function ($description, $value) {
            return [
                'content' => $description,
                'class' => (new DatacenterStatus($value))->badgeHtmlClass(),
            ];
        });
    }
}
