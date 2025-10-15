<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a paginated list of users with search functionality.
     *
     * @param  Request  $request  The HTTP request containing search parameters
     * @return View The users index view with paginated user data
     */
    public function index(Request $request): View
    {
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
     * @param  StoreUserRequest  $request  The validated request containing user data
     * @return RedirectResponse Redirect to users index with success message
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);
        $user->assignRole('secretary');

        // Give default permissions as direct permissions (not role permissions)
        $defaultPermissions = [
            'view_classes',
            'view_students',
            'generate_certificates',
            'generate_cards',
            'generate_attendance_lists',
        ];
        $user->givePermissionTo($defaultPermissions);

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
     * @param  UpdateUserRequest  $request  The validated request containing updated user data
     * @param  User  $user  The user model instance to update
     * @return RedirectResponse Redirect to users index with success message
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
     * @param  User  $user  The user model instance to delete
     * @return RedirectResponse Redirect to users index with success message
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Membre du personnel supprimé avec succès.');
    }
}
