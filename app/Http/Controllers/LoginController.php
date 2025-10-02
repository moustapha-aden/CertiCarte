<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle user authentication attempt.
     *
     * Validates user credentials and attempts to authenticate the user.
     * Supports "remember me" functionality and provides role-based redirection.
     * Regenerates session for security after successful login.
     *
     * @param  Request  $request  The HTTP request containing login credentials
     * @return \Illuminate\Http\RedirectResponse Redirect to dashboard or back with errors
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function authenticate(Request $request)
    {
        // Validation des champs email et mot de passe
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Récupérer l'utilisateur pour vérifier son rôle
            $user = Auth::user();

            // Redirection conditionnelle en fonction du rôle
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/dashboard');
                case 'secretary':
                    return redirect()->intended('/dashboard');
                default:
                    // Redirection par défaut si le rôle n'est pas reconnu
                    return redirect()->intended('/dashboard');
            }
        }

        // Si l'authentification échoue, retour en arrière avec une erreur
        return back()->withErrors([
            'email' => 'Les informations fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     *
     * Logs out the authenticated user, invalidates the session,
     * and regenerates the CSRF token for security.
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
