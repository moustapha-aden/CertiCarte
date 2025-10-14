<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing users in the system.
 *
 * Handles CRUD operations for user accounts including creation,
 * modification, viewing, and deletion of user records.
 *
 * @author Lycée Ahmed Farah Ali
 *
 * @version 1.0.0
 */
class UserController extends Controller
{
    /**
     * Display a paginated list of users with search functionality.
     * Only shows secretary users for role management.
     *
     * Supports filtering by name, email through the 'q' parameter.
     * Results are ordered by creation date (newest first) and paginated.
     *
     * @param  Request  $request  The HTTP request containing search parameters
     * @return View The users index view with paginated user data
     */
    public function index(Request $request): View
    {
        // Only show secretary users
        $query = User::role('secretary')->with(['permissions', 'roles']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%");
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View The user creation form view
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in the database.
     *
     * Validates the incoming request data and creates a new user record.
     * Password is automatically hashed by the User model's mutator.
     * Automatically assigns the 'secretary' role to the new user.
     *
     * @param  StoreUserRequest  $request  The validated request containing user data
     * @return RedirectResponse Redirect to users index with success message
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);
        $user->assignRole('secretary');

        return redirect()->route('users.index')->with('success', 'Membre du personnel '.$user->name.' créé avec succès.');
    }

    /*
    function pour le prof ile
     */
    public function showProfile(): View
    {
        $user = auth()->user();
        return view('users.profil', compact('user'));
    }
    /**
     * Display the specified user's details.
     *
     * @param  User  $user  The user model instance to display
     * @return View The user details view
     */
    public function show(User $user): View
    {
        $user->load(['permissions', 'roles']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  User  $user  The user model instance to edit
     * @return View The user edit form view
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in the database.
     *
     * Validates the incoming request data and updates the user record.
     * Password is only updated if provided in the request.
     *
     * @param  UpdateUserRequest  $request  The validated request containing updated user data
     * @param  User  $user  The user model instance to update
     * @return RedirectResponse Redirect to users index with success message
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validatedData = $request->validated();

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($request->filled('password')) {
            $user->password = $validatedData['password'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Membre du personnel '.$user->name.' mis à jour avec succès.');
    }

    /**
     * Remove the specified user from the database.
     *
     * Permanently deletes the user record. This action cannot be undone.
     *
     * @param  User  $user  The user model instance to delete
     * @return RedirectResponse Redirect to users index with success message
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Membre du personnel supprimé avec succès.');
    }
}
