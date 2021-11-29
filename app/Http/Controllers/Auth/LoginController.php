<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;

use App\Repositories\SettingRepository;

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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->onlyInRules();

        $logginInUser = db('users')
            ->where('username', $credentials['identity'])
            ->orWhere('email', $credentials['identity'])
            ->first();

        // Attempt login
        $attempt = auth()->attempt([
            'email' => $logginInUser->email, 
            'password' => $credentials['password'],
        ]);

        if ($attempt) {
            activity()
                ->causedBy(auth()->user())
                ->log(auth()->user()->anchorName() . ' had logged in to administrator dashboard.');

            // Load Site Settings
            $settingRepository = new SettingRepository;
            $settings = $settingRepository->allSettings();

            // Save in session
            foreach ($settings as $key => $value)
                $request->session()->put($key, $value);
        }

        return $attempt ?
            redirect()->intended('dashboard') :
            redirect()->back()->with('error', 'Login attempt failed.');
    }
}