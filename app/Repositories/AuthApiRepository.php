<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\User;

class AuthApiRepository extends BaseRepository
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
	 * Authenticate any user from supplied arguments
	 * 
	 * @param array  $credentials
	 * @return \App\Models\User|null
	 */
	public function login(array $credentials)
	{
		try {
			// Collect credentials
			$identity = $credentials['identity'];
			$password = $credentials['password'];

			if (! $user = User::findByIdentity($identity)) {
				$this->setNotFound('User not found!');
				return;
			}

			// Check if password matched the record
			if (! $user->isPasswordMatch($password)) {
				$this->setUnprocessedInput('Password mismatch the record!');
				return;
			}

			// Generate API Authentication Token
			$user->generateToken();

			$this->setSuccess('Successfully login.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to do login.', $error);
			return;
		}

		return $user;
	}

	/**
	 * Register user
	 * 
	 * @param array  $registration
	 * @return \App\Models\User
	 */
	public function register(array $registration)
	{
		try {
			$user = $this->getModel();
			$user->fill($registration);
			$user->save();

			$this->setSuccess('Successfully registered as user, please confirm email address to complete.');
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to register as user, we don\'t know why', $error);
		}

		return $user;
	}

	/**
	 * Logout the user
	 * 
	 * @return bool
	 */
	public function logout()
	{
		try {
			$user = $this->getModel();
			$logout = $user->tokens()->delete();

			$this->setSuccess('Successfully logged out.');
			return $logout;
		} catch (QueryException $qe) {
			$error = $qe->getMessage();
			$this->setError('Failed to log out', $error);
		}

		return $user;
	}
}
