<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('login');
    }

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

            // Redirection fiable : utiliser l’URL "intended" seulement si elle est interne et n’est pas la page de connexion
            $intended = $request->session()->pull('url.intended');
            $dashboardUrl = route('dashboard');

            if ($intended && $intended !== route('login') && $intended !== url('/login')) {
                $parsed = parse_url($intended);
                // URL relative (ex. /dashboard/students) : accepter si ce n’est pas la page de login
                if (empty($parsed['host'])) {
                    $path = $parsed['path'] ?? '';
                    if (str_starts_with($path, '/') && !str_starts_with($path, '/login')) {
                        return redirect()->to($intended);
                    }
                } else {
                    // URL absolue : accepter seulement si même domaine que l’app
                    $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                    if (isset($appHost) && strtolower($parsed['host']) === strtolower($appHost)) {
                        return redirect()->to($intended);
                    }
                }
            }

            return redirect()->to($dashboardUrl);
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

   /*-----------------------------------------------------
-------------------- Set Password (Forgot Password) --------------------
-----------------------------------------------------*/

/**
 * Affiche le formulaire où l'utilisateur entre son email
 * pour recevoir le lien de réinitialisation.
 */
public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}


/**
 * Envoie le lien de réinitialisation à l'adresse email fournie.
 * 
 * Étapes :
 * 1. Validation de l'email
 * 2. Envoi du token par email
 * 3. Retour avec message de succès ou erreur
 */
public function sendResetLinkEmail(Request $request)
{
    // Validation des données
    $request->validate([
        'email' => ['required', 'email'],
    ]);

    // Génération et envoi du lien de réinitialisation
    $status = Password::sendResetLink(
        $request->only('email')
    );

    // Vérification du statut retourné par Laravel
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status)) // Succès
        : back()->withErrors(['email' => __($status)]); // Erreur
}


/**
 * Affiche le formulaire permettant de définir un nouveau mot de passe.
 * 
 * Le token est récupéré depuis l'URL et envoyé à la vue.
 */
public function showResetPasswordForm($token)
{
    return view('auth.reset-password', ['token' => $token]);
}


/**
 * Traite la réinitialisation du mot de passe.
 * 
 * Étapes :
 * 1. Validation du token + email + mot de passe
 * 2. Vérification du token
 * 3. Mise à jour du mot de passe (hash sécurisé)
 * 4. Suppression automatique du token
 */
public function resetPassword(Request $request)
{
    // Validation des données reçues
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    // Tentative de réinitialisation
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {

            // Mise à jour du mot de passe avec hash sécurisé
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    // Redirection selon résultat
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}



}
