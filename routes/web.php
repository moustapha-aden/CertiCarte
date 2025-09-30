<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//                     ======
// PUBLIC ROUTES (No Authentication Required)
//                     ======

/**
 * Home page route - redirects authenticated users to dashboard
 *
 * @route GET /
 *
 * @name login
 *
 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {

    // Tableau de bord dynamique
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des étudiants
    Route::resource('/dashboard/students', StudentController::class);
    Route::resource('/dashboard/classes', ClasseController::class);
    // Ajout de la route manquante pour afficher les élèves d'une classe spécifique
    Route::get('/dashboard/classes/{classe}/students', [ClasseController::class, 'show'])->name('classes.students');

    // NOUVELLE LIGNE (Ajout de /dashboard/ pour correspondre à votre URL)
    Route::get('/dashboard/students/{student}/certificate', [StudentController::class, 'generateCertificate'])->name('students.certificate');
});

// Réinitialisation de mot de passe

/**
 * Password reset request page
 *
 * @route GET /password/request
 *
 * @name password.request
 *
 * @return string
 */

Route::get('/password/request', function () {
    return 'Page de demande de réinitialisation du mot de passe.';
})->name('password.request');

//                     ======
// PROTECTED ROUTES (Authentication Required)
//                     ======

/**
 * Authenticated routes group
 * All routes within this group require user authentication
 */
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //                     ==
    // STUDENT MANAGEMENT ROUTES
    //                     ==

    /**
     * Student resource routes
     * Provides full CRUD operations for student management
     *
     * Generated routes:
     * - GET    /dashboard/students           (index)   - List all students
     * - GET    /dashboard/students/create    (create)  - Show create form
     * - POST   /dashboard/students           (store)   - Store new student
     * - GET    /dashboard/students/{id}      (show)    - Show specific student
     * - GET    /dashboard/students/{id}/edit (edit)    - Show edit form
     * - PUT    /dashboard/students/{id}      (update)  - Update student
     * - DELETE /dashboard/students/{id}       (destroy) - Delete student
     *
     * @route resource /dashboard/students
     *
     * @uses StudentController
     */
    Route::resource('/dashboard/students', StudentController::class);

    //                     ==
    // CLASSE MANAGEMENT ROUTES
    //                     ==

    /**
     * Classe resource routes
     * Provides full CRUD operations for classe management
     *
     * Generated routes:
     * - GET    /dashboard/classes           (index)   - List all classes
     * - GET    /dashboard/classes/create    (create)  - Show create form
     * - POST   /dashboard/classes           (store)   - Store new classe
     * - GET    /dashboard/classes/{classe}  (show)    - Show specific classe
     * - GET    /dashboard/classes/{classe}/edit (edit)    - Show edit form
     * - PUT    /dashboard/classes/{classe}  (update)  - Update classe
     * - DELETE /dashboard/classes/{classe}  (destroy) - Delete classe
     *
     * @route resource /dashboard/classes
     *
     * @uses ClasseController
     */
    Route::get('/dashboard/classes', [ClasseController::class, 'index'])->name('classes.index');
    Route::get('/dashboard/classes/create', [ClasseController::class, 'create'])->name('classes.create');
    Route::post('/dashboard/classes', [ClasseController::class, 'store'])->name('classes.store');
    Route::get('/dashboard/classes/{classe}', [ClasseController::class, 'show'])->name('classes.show');
    Route::get('/dashboard/classes/{classe}/edit', [ClasseController::class, 'edit'])->name('classes.edit');
    Route::put('/dashboard/classes/{classe}', [ClasseController::class, 'update'])->name('classes.update');
    Route::delete('/dashboard/classes/{classe}', [ClasseController::class, 'destroy'])->name('classes.destroy');

    // ========================================================================
    // USER MANAGEMENT ROUTES
    // ========================================================================

    /**
     * User resource routes
     * Provides full CRUD operations for user management
     *
     * Generated routes:
     * - GET    /dashboard/users           (index)   - List all users
     * - GET    /dashboard/users/create    (create)  - Show create form
     * - POST   /dashboard/users           (store)   - Store new user
     * - GET    /dashboard/users/{user}    (show)    - Show specific user
     * - GET    /dashboard/users/{user}/edit (edit)    - Show edit form
     * - PUT    /dashboard/users/{user}    (update)  - Update user
     * - DELETE /dashboard/users/{user}    (destroy) - Delete user
     *
     * @route resource /dashboard/users
     *
     * @uses UserController
     */
    Route::resource('/dashboard/users', UserController::class);

    // API Routes
    Route::get('/api/classes/by-year/{yearId}', [StudentController::class, 'getClassesByYear'])->name('api.classes.by-year');
});
