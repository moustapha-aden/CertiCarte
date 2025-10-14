<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle user authentication attempt.
     *
     * @param  Request  $request  The HTTP request containing login credentials
     * @return \Illuminate\Http\RedirectResponse Redirect to dashboard or back with errors
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Les informations fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     *
     * @param  Request  $request  The HTTP request containing session data
     * @return \Illuminate\Http\RedirectResponse Redirect to home page
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
