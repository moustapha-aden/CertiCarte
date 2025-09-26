<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Page de connexion (nommée 'login')
Route::get('/', function () {
    return view('login');
})->name('login');

// Soumission du formulaire de connexion
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {

    // Accueil simple
    Route::get('/home', function () {
        return 'Bienvenue !';
    });

    // Tableau de bord dynamique
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des étudiants
    Route::resource('students', StudentController::class);
});

// Réinitialisation de mot de passe
Route::get('/password/request', function () {
    return 'Page de demande de réinitialisation du mot de passe.';
})->name('password.request');
