<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleManagementController;
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
     * Student resource routes with granular CRUD permissions
     * Each action requires specific permission
     *
     * Generated routes:
     * - GET    /dashboard/students           (index)   - List all students (view_students)
     * - GET    /dashboard/students/create    (create)  - Show create form (create_students)
     * - POST   /dashboard/students           (store)   - Store new student (create_students)
     * - GET    /dashboard/students/{id}      (show)    - Show specific student (view_students)
     * - GET    /dashboard/students/{id}/edit (edit)    - Show edit form (edit_students)
     * - PUT    /dashboard/students/{id}      (update)  - Update student (edit_students)
     * - DELETE /dashboard/students/{id}     (destroy) - Delete student (delete_students)
     *
     * @route resource /dashboard/students
     *
     * @uses StudentController
     */
    Route::get('/dashboard/students', [StudentController::class, 'index'])
        ->name('students.index')
        ->middleware('permission:view_students');

    Route::get('/dashboard/students/create', [StudentController::class, 'create'])
        ->name('students.create')
        ->middleware('permission:create_students');

    Route::post('/dashboard/students', [StudentController::class, 'store'])
        ->name('students.store')
        ->middleware('permission:create_students');

    Route::get('/dashboard/students/{student}', [StudentController::class, 'show'])
        ->name('students.show')
        ->middleware('permission:view_students');

    Route::get('/dashboard/students/{student}/edit', [StudentController::class, 'edit'])
        ->name('students.edit')
        ->middleware('permission:edit_students');

    Route::put('/dashboard/students/{student}', [StudentController::class, 'update'])
        ->name('students.update')
        ->middleware('permission:edit_students');

    Route::delete('/dashboard/students/{student}', [StudentController::class, 'destroy'])
        ->name('students.destroy')
        ->middleware('permission:delete_students');

    /**
     * Student import route
     * Imports students from Excel/CSV file
     * Requires 'import_students' permission
     *
     * @route POST /dashboard/students/import
     *
     * @name students.import
     *
     * @uses StudentController@import
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    Route::post('/dashboard/students/import', [StudentController::class, 'import'])
        ->name('students.import')
        ->middleware('permission:import_students');

    // ========================================================================
    // CLASSE MANAGEMENT ROUTES
    // ========================================================================

    /**
     * Classe resource routes with granular CRUD permissions
     * Each action requires specific permission
     *
     * Generated routes:
     * - GET    /dashboard/classes           (index)   - List all classes (view_classes)
     * - GET    /dashboard/classes/create    (create)  - Show create form (create_classes)
     * - POST   /dashboard/classes           (store)   - Store new classe (create_classes)
     * - GET    /dashboard/classes/{classe}  (show)    - Show specific classe (view_classes)
     * - GET    /dashboard/classes/{classe}/edit (edit)    - Show edit form (edit_classes)
     * - PUT    /dashboard/classes/{classe}  (update)  - Update classe (edit_classes)
     * - DELETE /dashboard/classes/{classe}  (destroy) - Delete classe (delete_classes)
     *
     * @route resource /dashboard/classes
     *
     * @uses ClasseController
     */
    Route::get('/dashboard/classes', [ClasseController::class, 'index'])
        ->name('classes.index')
        ->middleware('permission:view_classes');

    Route::get('/dashboard/classes/create', [ClasseController::class, 'create'])
        ->name('classes.create')
        ->middleware('permission:create_classes');

    Route::post('/dashboard/classes', [ClasseController::class, 'store'])
        ->name('classes.store')
        ->middleware('permission:create_classes');

    Route::get('/dashboard/classes/{classe}', [ClasseController::class, 'show'])
        ->name('classes.show')
        ->middleware('permission:view_classes');

    Route::get('/dashboard/classes/{classe}/edit', [ClasseController::class, 'edit'])
        ->name('classes.edit')
        ->middleware('permission:edit_classes');

    Route::put('/dashboard/classes/{classe}', [ClasseController::class, 'update'])
        ->name('classes.update')
        ->middleware('permission:edit_classes');

    Route::delete('/dashboard/classes/{classe}', [ClasseController::class, 'destroy'])
        ->name('classes.destroy')
        ->middleware('permission:delete_classes');

    // ========================================================================
    // USER MANAGEMENT ROUTES
    // ========================================================================

    /**
     * User resource routes with granular CRUD permissions
     * Each action requires specific permission for user management
     *
     * Generated routes:
     * - GET    /dashboard/users           (index)   - List all secretary users (view_users)
     * - GET    /dashboard/users/create    (create)  - Show create form (create_users)
     * - POST   /dashboard/users           (store)   - Store new secretary user (create_users)
     * - GET    /dashboard/users/{user}    (show)    - Show specific user (view_users)
     * - GET    /dashboard/users/{user}/edit (edit)    - Show edit form (edit_users)
     * - PUT    /dashboard/users/{user}    (update)  - Update user (edit_users)
     * - DELETE /dashboard/users/{user}    (destroy) - Delete user (delete_users)
     *
     * @route resource /dashboard/users
     *
     * @uses UserController
     */
    Route::get('/dashboard/users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('permission:view_users');

    Route::get('/dashboard/users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('permission:create_users');

    Route::post('/dashboard/users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('permission:create_users');

    Route::get('/dashboard/users/{user}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('permission:view_users');

    Route::get('/dashboard/users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('permission:edit_users');

    Route::put('/dashboard/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('permission:edit_users');

    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('permission:delete_users');

    // ========================================================================
    // PERMISSION MANAGEMENT ROUTES
    // ========================================================================

    /**
     * Permission management routes
     * Provides permission management functionality for individual users
     * Requires 'edit_users' permission for managing user permissions
     *
     * Routes:
     * - POST   /dashboard/users/{user}/permissions (updatePermissions) - Update user permissions
     * - GET    /dashboard/users/{user}/permissions (getUserPermissions) - Get user permissions
     *
     * @uses RoleManagementController
     */
    Route::prefix('/dashboard/users')->name('users.')->group(function () {
        Route::post('/{user}/permissions', [RoleManagementController::class, 'updatePermissions'])
            ->name('update-permissions')
            ->middleware('permission:edit_users');
        Route::get('/{user}/permissions', [RoleManagementController::class, 'getUserPermissions'])
            ->name('get-permissions')
            ->middleware('permission:view_users');
    });

    // ========================================================================
    // REPORTS ROUTES
    // ========================================================================

    /**
     * Reports page route
     * Displays the reports generation interface
     *
     * @route GET /dashboard/reports
     *
     * @name reports.index
     *
     * @uses ReportsController@index
     *
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard/reports', [ReportsController::class, 'index'])
        ->name('reports.index')
        ->middleware('permission:generate_certificates|generate_cards|generate_attendance_lists');

    /**
     * Certificate generation route
     * Generates a PDF certificate for a specific student
     *
     * @route GET /reports/certificate/{student}
     *
     * @name reports.certificate
     *
     * @uses ReportsController@generateCertificate
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/certificate/{student}', [ReportsController::class, 'generateCertificate'])
        ->name('reports.certificate')
        ->middleware('permission:generate_certificates');

    /**
     * ID card generation route
     * Generates a PDF ID card for a specific student
     *
     * @route GET /reports/id-card/{student}
     *
     * @name reports.id_card
     *
     * @uses ReportsController@generateIdCard
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/id-card/{student}', [ReportsController::class, 'generateIdCard'])
        ->name('reports.id_card')
        ->middleware('permission:generate_cards');

    /**
     * Attendance list generation route
     * Generates a PDF attendance list for a class
     *
     * @route GET /reports/attendance-list/{classe}
     *
     * @name reports.attendance_list
     *
     * @uses ReportsController@generateAttendanceList
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/attendance-list/{classe}', [ReportsController::class, 'generateAttendanceList'])
        ->name('reports.attendance_list')
        ->middleware('permission:generate_attendance_lists');

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

    /**
     * API route to fetch students by class
     * Used for dynamic dropdown population via AJAX
     *
     * @route GET /api/students/by-class/{classe_id}
     *
     * @name api.students.by-class
     *
     * @param  int  $classeId  The class ID
     *
     * @uses ReportsController@getStudentsByClass
     *
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/api/students/by-class/{classeId}', [ReportsController::class, 'getStudentsByClass'])->name('api.students.by-class');
});
