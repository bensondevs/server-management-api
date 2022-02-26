<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Models\{ User, Role };
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository
{
	/**
	 * Repository constructor method
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	/**
	 * Save user
	 * 
	 * @param array  $userData
	 * @return \App\Models\User
	 */
	public function save(array $userData)
	{
		try {
			$user = $this->getModel();

			$user->fill($userData);
			$user->save();
			$this->setModel($user);

			$this->setSuccess('Successfully save user data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save user data', $error);
		}

		return $this->getModel();
	}

	/**
	 * Update user's password
	 * 
	 * @param  string  $password
	 * @return \App\Models\User
	 */
	public function updatePassword(string $password)
	{
		try {
			$user = $this->getModel();
			$user->unhashed_password = $password;
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully update user\'s password.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to update user\'s password.', $error);
		}

		return $this->getModel();
	}

	/**
	 * To enable/disable subscription to newsletter
	 * 
	 * @return \App\Models\User
	 */
	public function toggleNewsletter()
	{
		try {
			$user = $this->getModel();
			$user->newsletter = (! $user->newsletter);
			$user->save();

			$this->setSuccess('Successfully update user newsletter subscription.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to update user newsletter subscription.', $error);
		}

		return $this->getModel();
	}

	/**
	 * Verify user's email
	 * 
	 * @return \App\Models\User
	 */
	public function verifyEmail()
	{
		try {
			$user = $this->getModel();
			$user->verifyEmail();
			$this->setModel($user);

			$this->setSuccess('Successfully verify email address');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to verify email address', $error);
		}

		return $this->getModel();
	}

	/**
	 * Delete user model record
	 * 
	 * @return bool
	 */
	public function delete()
	{
		try {
			$user = $this->getModel();
			$user->delete();
			$this->destroyModel();

			$this->setSuccess('Successfully delete user.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to delet user', $error);
		}

		return $this->returnResponse();
	}
}
