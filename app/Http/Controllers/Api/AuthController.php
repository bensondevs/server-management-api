<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\{ 
    LoginRequest, 
    RegisterRequest, 
    ForgotPasswordRequest,
    ResetPasswordRequest
};
use App\Repositories\AuthApiRepository;
use App\Models\PasswordReset;

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
        $exists =  User::whereUsername('username')->exists();

        return response()->json(['available' => (! $exists)]);
    }

    /**
     * Check if email is available
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function checkEmailAvailable(Request $request)
    {
        $email = $request->input('email');
        $exists = User::whereEmail($email)->exists();

        return response()->json(['available' => (! $exists)]);
    }

    /**
     * Request to reset password.
     * 
     * This request will send email containing token
     * for resetting password. Token should be attached to 
     * the login form in order to allow the user to reset their
     * password.
     * 
     * @param  \App\Http\Requests\Auth\ForgotPasswordRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->input('email');
        PasswordReset::create(['email' => $email]);

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully create password reset token. The token will be sent to your email shortly.',
        ]);
    }

    /**
     * Check reset token validity
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function checkResetTokenValidity(Request $request)
    {
        $token = $request->input('token');
        $valid = PasswordReset::whereToken($token)->exists();

        return response()->json(['valid' => $valid]);
    }

    /**
     * Reset user's password.
     * 
     * This will require token that has been sent to user's email.
     * 
     * @param  \App\Http\Request\Auth\ResetPasswordRequest  $request
     * @return \Illuminate\Support\Facades\Response
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $user = User::findByIdentity($email);
        $this->auth->setModel($user);

        $password = $request->input('password');
        $this->auth->updatePassword($password);

        return apiResponse($this->auth);
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
