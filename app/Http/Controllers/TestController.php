<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\CheckExpiredContainer;

class TestController extends Controller
{
    public function test()
    {
    	$testJob = new CheckExpiredContainer();
		$testJob->delay(carbon()->now()->addSeconds(1));
		dispatch($testJob);

		return 'Job executed';
    }
}
