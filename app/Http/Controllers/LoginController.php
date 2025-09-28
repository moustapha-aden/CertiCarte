<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Gère la tentative d'authentification.
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
                    return redirect()->intended('/home');
            }
        }

        // Si l'authentification échoue, retour en arrière avec une erreur
        return back()->withErrors([
            'email' => 'Les informations fournies ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    /**
     * Gère la déconnexion de l'utilisateur.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
