<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\Auth\Guard;
use App\User;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
        {
            $this->middleware('guest')->except('logout');

            $this->Auth = $auth;
        }

    public function username()
        {
            return 'username';
        }

     public function login(LoginRequest $request)
    {        
        $request = $request->all();
        $request['activated'] = User::ACTIVE_USER;
        unset($request['_token']);
        

        if ($this->Auth->attempt($request)) {
            return redirect()->intended($this->redirectTo);
        }
        
        return redirect()->back()
                ->with('message',__('auth.failed'))
                ->with('status', 'danger')
                ->withInput();
    }
    
    public function logout(Request $request)
    {
        $this->guard()->logout();
        return redirect('login')
                ->with('message', __('auth.logout'))
                ->with('status', 'success');
    }
}
