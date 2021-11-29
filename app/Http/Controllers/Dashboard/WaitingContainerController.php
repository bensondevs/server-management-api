<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WaitingContainer;

class WaitingContainerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $containers = WaitingContainer::all();
            return response()->json(['waiting_containers' => $containers]);
        }

        return view('dashboard.waiting_containers.index');
    }
}