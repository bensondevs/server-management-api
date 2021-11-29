<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;

use App\Repositories\Base\BaseRepository;

use App\Models\Notification;

class NotificationRepository extends BaseRepository
{
	private $start;
	private $end;

	private $user;

	public function __construct()
	{
		$this->setInitModel(new Notification);
	}

	public function setStart($start)
	{
		return $this->start = $start;
	}

	public function setEnd($end)
	{
		return $this->end = $end;
	}

	public function setUser(User $user)
	{
		return $this->user = $user;
	}

	public function notifications()
	{
		$notifications = $this->getModel();

		if ($user = $this->user)
			$notifications = $notifications->where('user_id', $user->id);

		if ($start = $this->start)
			$notifications = $notifications->where('created_at', '>=', $start);

		if ($end = $this->end)
			$notifications = $notifications->where('created_at', '<=', $end);

		return $this->setCollection($notifications->get());
	}

	public function sendNotification(array $notificationData)
	{
		try {
			$notification = $this->getModel();
			$notification->fill($notificationData);
			$notification->save();

			$this->setModel($notification);

			$this->setSuccess('Successfully send notification.');
		} catch (QueryException $qe) {
			$this->setError(
				'Failed to send notification', 
				$qe->getMessage()
			);
		}

		return $this->getModel();
	}
}
