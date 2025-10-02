<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

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

/**
 * User authentication route
 *
 * @route POST /login
 *
 * @name authenticate
 *
 * @uses LoginController@authenticate
 *
 * @return \Illuminate\Http\RedirectResponse
 */
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

/**
 * User logout route
 *
 * @route POST /logout
 *
 * @name logout
 *
 * @uses LoginController@logout
 *
 * @return \Illuminate\Http\RedirectResponse
 */
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================================
// PROTECTED ROUTES (Authentication Required)
// ============================================================================

/**
 * Authenticated routes group
 * All routes within this group require user authentication
 */
Route::middleware('auth')->group(function () {

    /**
     * Dashboard main page
     *
     * @route GET /dashboard
     *
     * @name dashboard
     *
     * @uses DashboardController@index
     *
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================================================================
    // STUDENT MANAGEMENT ROUTES
    // ========================================================================

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
     * - DELETE /dashboard/students/{id}     (destroy) - Delete student
     *
     * @route resource /dashboard/students
     *
     * @uses StudentController
     */
    Route::resource('/dashboard/students', StudentController::class);

    /**
     * Student certificate generation route
     * Generates a PDF certificate for a specific student
     *
     * @route GET /dashboard/students/{student}/certificate
     *
     * @name students.certificate
     *
     * @param  \App\Models\Student  $student  The student to generate certificate for
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/dashboard/students/{student}/certificate', [StudentController::class, 'generateCertificate'])->name('students.certificate');

    /**
     * Student import route
     * Imports students from Excel/CSV file
     *
     * @route POST /dashboard/students/import
     *
     * @name students.import
     *
     * @uses StudentController@import
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    Route::post('/dashboard/students/import', [StudentController::class, 'import'])->name('students.import');

    // ========================================================================
    // CLASSE MANAGEMENT ROUTES
    // ========================================================================

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

    // ========================================================================
    // API ROUTES
    // ========================================================================

    /**
     * API route to fetch classes by school year
     * Used for dynamic dropdown population via AJAX
     *
     * @route GET /api/classes/by-year/{year_id}
     *
     * @name api.classes.by-year
     *
     * @param  int  $yearId  The school year ID
     *
     * @uses StudentController@getClassesByYear
     *
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/api/classes/by-year/{yearId}', [StudentController::class, 'getClassesByYear'])->name('api.classes.by-year');
    Route::get('/classes/{classe}/liste-appel', [ClasseController::class, 'generateAttendanceList'])
    ->name('classes.attendance_list');
    // ... (autres routes)

    // Route pour la carte d'étudiant
    Route::get('/students/{student}/id-card', [StudentController::class, 'idCard'])
        ->name('students.id_card');
});
