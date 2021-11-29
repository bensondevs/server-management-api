<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\User;
use App\Models\Activity;

class ActivityLogRepository extends BaseRepository
{
	protected $start;
	protected $end;

	public function __construct()
	{
		$activity = new Activity();
		$this->setInitModel($activity);
	}

	public function setStart($datetime)
	{
		return $this->start = $datetime;
	}

	public function setEnd($datetime)
	{
		return $this->end = $datetime;
	}

	public function allActtivities()
	{
		$activities = Activity::whereNotNull('created_at');

		if ($this->start) {
			$activities = ($this->start) ?
				$activities->where('created_at', $this->start) :
				$activities;
		}

		if ($this->end) {
			$activities = ($this->end) ?
				$activities->where('created_at', $this->end) :
				$activities;
		}

		$activities = $activities->get();

		$this->setCollection($activities);

		return $this->getCollection();
	}

	public function activitiesOf(User $user)
	{
		$activities = Activity::where('causer_id', $user->id);

		if ($this->start) {
			$activities = ($this->start) ?
				$activities->where('created_at', $this->start) :
				$activities;
		}

		if ($this->end) {
			$activities = ($this->end) ?
				$activities->where('created_at', $this->end) :
				$activities;
		}

		$activities = $activities->get();

		$this->setCollection($activities);

		return $this->getCollection();
	}
}
