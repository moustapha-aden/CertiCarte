<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasseController;

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
    Route::resource('classes', ClasseController::class);
    // Ajout de la route manquante pour afficher les élèves d'une classe spécifique
    Route::get('/classes/{classe}/students', [ClasseController::class, 'show'])->name('classes.students');
});

// Réinitialisation de mot de passe
Route::get('/password/request', function () {
    return 'Page de demande de réinitialisation du mot de passe.';
})->name('password.request');
