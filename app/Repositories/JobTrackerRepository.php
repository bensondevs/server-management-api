<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\JobTracker;

class JobTrackerRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new JobTracker());
	}
}
