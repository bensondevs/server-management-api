<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Repositories\Base\BaseRepository;

use App\Models\User;

class AuthApiRepository extends BaseRepository
{
	public function __construct()
	{
		$this->setInitModel(new User);
	}

	public function login(array $credentials)
	{
		try {
			// Collect credentials
			$identity = $credentials['identity'];
			$password = $credentials['password'];

			// Find user
			$user = $this->getModel()->where('email', $identity)
				->orWhere('username', $identity)
				->first();
			if (! $user) {
				$this->setNotFound('User not found!');
				return null;
			}
			$this->setModel($user); // Found!

			// Check if password matched the record
			if (! hashCheck($password, $user->password)) {
				$this->setUnprocessedInput('Password mismatch the record!');
				return null;
			}

			// API Login Token
			$user->token = $user->createToken(time())->plainTextToken;

			$this->setModel($user);

			$this->setSuccess('Successfully login');
		} catch (QueryException $qe) {
			$this->setError('Failed to do login, there is something wrong and we don\' know yet', $qe->getMessage());
		}

		return $this->getModel();
	}

	public function register(array $registration)
	{
		try {
			// Create user
			$user = $this->getModel();
			$user->fill($registration);
			$user->save();

			$this->setSuccess('Successfully registered as user, please confirm email address to complete .');
		} catch (QueryException $qe) {
			$this->setError('Failed to register as user, we don\'t know why', $qe->getMessage());
		}
	}

	public function logout()
	{
		try {
			$user = $this->getModel();
			$user->tokens()->delete();

			$this->destroyModel();

			$this->setSuccess('Successfully logged out.');
		} catch (QueryException $qe) {
			$this->setError('Failed to log out', $qe->getMessage());
		}

		return null;
	}
}
