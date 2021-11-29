<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\Role;

use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	public function allWithoutAdmins()
	{
		$this->setCollection(User::doesntHave('roles')->get());
		return $this->getCollection();
	}

	public function administrators()
	{
		$role = Role::findByName('administrator');
		$administrators = $role->users()->get();
		$this->setCollection($administrators);
		
		return $this->getCollection();
	}

	public function save($userData)
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

	public function updateProfile($userProfileData)
	{
		try {
			$user = $this->getModel();
			$user->first_name = $userProfileData['first_name'];
			$user->middle_name = isset($userProfileData['middle_name']) ?
				$userProfileData['middle_name'] : null;
			$user->last_name = $userProfileData['last_name'];
			$user->company_name = isset($userProfileData['company_name']) ?
				$userProfileData['company_name'] : null;
			$user->save();
			$this->setModel($user);

			$this->setSuccess('Successfully save user profile data.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to save user profile data.', $error);
		}

		return $this->getModel();
	}

	public function updateAccount($userAccountData)
	{
		try {
			$user = $this->getModel();
			$user->email = $userAccountData['email'];
			$user->username = $userAccountData['username'];
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully update user account information.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to update user account information.', $error);
		}

		return $this->getModel();
	}

	public function updatePassword($userPasswordData)
	{
		try {
			$user = $this->getModel();
			$user->unhashed_password = $userPasswordData['password'];
			$user->save();

			$this->setModel($user);

			$this->setSuccess('Successfully change user password.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to change user password.', $error);
		}

		return $this->getModel();
	}

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
