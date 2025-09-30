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

Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student Management
    Route::resource('/dashboard/students', StudentController::class);
    Route::get('/dashboard/students/{student}/certificate', [StudentController::class, 'generateCertificate'])->name('students.certificate');

    // Class Management
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
