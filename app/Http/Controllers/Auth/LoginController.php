<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
       // $this->middleware('auth')->only('logout');
    }

    /**
     * Run after successful authentication.
     */
   /*  protected function authenticated(Request $request, $user)
    {
        if (!$request->hasSession()) {
            \Log::error('Session not available on login.');
            return redirect('/')->withErrors('Session not started. Please refresh and try again.');
        }

        session()->regenerate();
        session(['debug_session_test' => true]);

        \Log::info('Authenticated. Session ID: ' . session()->getId());
        \Log::info('Session Data: ', session()->all());

        return redirect()->intended($this->redirectPath());
    }  */
    
}

