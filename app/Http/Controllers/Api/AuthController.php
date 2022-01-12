<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\{ LoginRequest, RegisterRequest };
use App\Repositories\AuthApiRepository;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Authentication Controller
    |--------------------------------------------------------------------------
    | This controller is responsible for handling authentication module.
    */

    /**
     * Authentication repository class container
     * 
     * @var \App\Repositories\AuthApiRepository
     */
    private $auth;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\AuthApiRepository  $auth
     * @return void
     */
    public function __construct(AuthApiRepository $auth)
    {
    	$this->auth = $auth;
    }

    /**
     * Handle login request and return user data
     * 
     * @param LoginRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function login(LoginRequest $request)
    {
        $input = $request->validated();
    	$user = $this->auth->login($input);

        if ($this->auth->status == 'error') {
            return apiResponse($this->auth);
        }

        $token = $user->token;
        $user = new UserResource($user);
        return apiResponse($this->auth, [
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Check token in headers validity
     * 
     * @return \Illuminate\Support\Facades\Response
     */
    public function checkTokenValidity()
    {
        $isValid = auth('sanctum')->check();

        return response()->json(['valid' => $isValid]);
    }

    /**
     * Check if username is available
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function checkUsernameAvailable(Request $request)
    {
        $username = $request->input('username');
        $exists =  db('users')->where('username', $username)->exists();

        return response()->json(['available' => (! $exists)]);
    }

    /**
     * Check if email is available
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function checkEmailAvailable()
    {
        $email = $request->input('email');
        $exists = db('users')->where('email', $email)->exists();

        return response()->json(['available' => (! $exists)]);
    }

    /**
     * Handle register request
     * 
     * @param RegisterRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();
        $user = $this->auth->register($input);
        return apiResponse($this->auth);
    }
    
    /**
     * Handle logout request and destroy user token
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $this->auth->logout($user);
        return apiResponse($this->auth);
    }
}
