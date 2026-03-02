<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Home page route - redirects authenticated users to dashboard.
 *
 * @return \Illuminate\Http\RedirectResponse
 */
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

/**
 * User authentication route.
 *
 * @uses LoginController
 */
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

/**
 * User logout route.
 *
 * @uses LoginController@logout
 *
 * @return \Illuminate\Http\RedirectResponse
 */
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');

Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('password.reset');

Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

/**
 * Authenticated routes group - all routes require authentication.
 */
Route::middleware('auth')->group(function () {

    /**
     * Dashboard main page.
     *
     * @uses DashboardController@index
     *
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Student import routes.
     * Requires 'import_students' permission.
     *
     * @uses StudentImportController
     */
    Route::get('/dashboard/students/imports', [StudentImportController::class, 'index'])
        ->name('students-import.index')
        ->middleware('permission:import_students');

    Route::post('/dashboard/students/imports', [StudentImportController::class, 'store'])
        ->name('students-import.store')
        ->middleware('permission:import_students');

    Route::get('/dashboard/students/imports/{id}', [StudentImportController::class, 'show'])
        ->name('students-import.show')
        ->middleware('permission:import_students');

    Route::get('/dashboard/students/imports/{id}/export-errors', [StudentImportController::class, 'exportErrors'])
        ->name('students-import.export-errors')
        ->middleware('permission:import_students');

    Route::delete('/dashboard/students/imports/{id}/errors', [StudentImportController::class, 'destroyErrors'])
        ->name('students-import.destroy-errors')
        ->middleware('permission:import_students');

    /**
     * Bulk photo import route.
     * Requires 'import_students' permission.
     *
     * @uses StudentController@importPhotos
     */
    Route::post('/dashboard/students/import-photos', [StudentController::class, 'importPhotos'])
        ->name('students.import-photos')
        ->middleware('permission:import_students');

    Route::get('/dashboard/students/photo-import-report/{reportId}', [StudentController::class, 'showPhotoImportReport'])
        ->name('students.photo-import-report')
        ->middleware('permission:import_students');

    /**
     * Student resource routes with granular CRUD permissions.
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
     * Class resource routes with granular CRUD permissions.
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

    /**
     * User resource routes with granular CRUD permissions.
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

    /**
     * Profile routes for authenticated users.
     * No specific permissions required.
     *
     * @uses ProfileController
     */
    Route::get('/dashboard/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/dashboard/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/dashboard/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    /**
     * Permission management routes.
     * Requires 'edit_users' permission.
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

    /**
     * Reports page route.
     *
     * @uses ReportsController@index
     *
     * @return \Illuminate\View\View
     */
    Route::get('/dashboard/reports', [ReportsController::class, 'index'])
        ->name('reports.index')
        ->middleware('permission:generate_certificates|generate_cards|generate_attendance_lists');

    /**
     * Certificate generation route.
     *
     * @uses ReportsController@generateCertificate
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/certificate/{student}', [ReportsController::class, 'generateCertificate'])
        ->name('reports.certificate')
        ->middleware('permission:generate_certificates');

    /**
     * ID card generation route.
     *
     * @uses ReportsController@generateIdCard
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/id-card/{student}', [ReportsController::class, 'generateIdCard'])
        ->name('reports.id_card')
        ->middleware('permission:generate_cards');


    // add route for attendance list carte
    Route::get('/classes/{classe}/identity-cards/print',
        [ClasseController::class, 'generateIdentityCards'])
        ->name('classes.identity_cards.print')
        ->middleware('permission:generate_cards');
    /**
     * Attendance list generation route.
     *
     * @uses ReportsController@generateAttendanceList
     *
     * @return \Illuminate\Http\Response PDF stream response
     */
    Route::get('/reports/attendance-list/{classe}', [ReportsController::class, 'generateAttendanceList'])
        ->name('reports.attendance_list')
        ->middleware('permission:generate_attendance_lists');

    /**
     * API route to fetch classes by school year.
     *
     * @param  int  $yearId  The school year ID
     *
     * @uses StudentController@getClassesByYear
     *
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/api/classes/by-year/{yearId}', [StudentController::class, 'getClassesByYear'])->name('api.classes.by-year');

    /**
     * API route to fetch students by class.
     *
     * @param  int  $classeId  The class ID
     *
     * @uses ReportsController@getStudentsByClass
     *
     * @return \Illuminate\Http\JsonResponse
     */
    Route::get('/api/students/by-class/{classeId}', [ReportsController::class, 'getStudentsByClass'])->name('api.students.by-class');
});
