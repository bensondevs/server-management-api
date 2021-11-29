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

    	return response()->json([
    		'user' => [
	    		'first_name' => $user->first_name,
	    		'middle_name' => $user->middle_name,
	    		'last_name' => $user->last_name,

	    		'country' => $user->country,
	    		'address' => $user->address,

	    		'username' => $user->username,
	    		'email' => $user->email,

	    		'company_name' => $user->company_name,
	    		'newsletter' => $user->newsletter,
	    	],
    	]);
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
