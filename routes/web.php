<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Route pour afficher la page de connexion (utilisable également via son nom 'login')
Route::get('/', function () {
    return view('login');
})->name('login');


// Route pour gérer la soumission du formulaire de connexion
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Route pour la déconnexion de l'utilisateur
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Route pour la page d'accueil (protégée par le middleware 'auth')
// L'utilisateur sera redirigé ici s'il est connecté.
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return 'Bienvenue !';
    });

    Route::get('/dashboard', function () {
        return view('Administrateur.Dashboard');
    });

    // Route::get('/dashboard', function () {
    //     return 'Tableau de bord';
    // });
});

Route::get('/password/request', function () {
    return 'Page de demande de réinitialisation du mot de passe.';
})->name('password.request');
