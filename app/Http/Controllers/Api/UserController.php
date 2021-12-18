<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Requests\Users\UpdateUserPasswordRequest as UpdatePasswordRequest;
use App\Repositories\UserRepository;

class UserController extends Controller
{
	/**
	 * User Repository Class Container
	 * 
	 * @var \App\Repositories\UserRepository
	 */
    private $user;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\UserRepository  $user
     * @return void
     */
    public function __construct(UserRepository $user)
    {
    	$this->user = $user;
    }

    /**
     * Get current authenticated user
     * 
     * @return Illuminate\Support\Facades\Response
     */
    public function current()
    {
    	$user = auth()->user();
        $user = new UserResource($user);

    	return response()->json(['user' => $user]);
    }

    /**
     * Update user password
     * 
     * @param UpdatePasswordRequest  $request
     * @param \App\Models\User  $user
     * @return Illuminate\Support\Facades\Response
     */
    public function updatePassword(UpdatePasswordRequest $request, User $user) 
    {
    	$this->user->setModel($user);

        $input = $request->validated();
    	$user = $this->user->updatePassword($input);

    	return apiResponse($this->user);
    }
}
