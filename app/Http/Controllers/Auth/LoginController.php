<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;

use App\Repositories\AuthApiRepository;

use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Authentication repository container
     * 
     * @var \App\Repositories\AuthApiRepository
     */
    private $auth;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthApiRepository $auth)
    {
        $this->middleware('guest')->except('logout');
        $this->auth = $auth;
    }

    /**
     * Login execution function
     * 
     * @param  App\Http\Requests\Auth\LoginRequest  $request
     * @return  \Illuminate\Support\Facades\Redirect
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validated();
        $this->auth->login($credentials);

        return $this->auth->status == 'success' ?
            redirect()->intended('dashboard') :
            redirect()->back()->with('error', 'Login attempt failed.');
    }
}