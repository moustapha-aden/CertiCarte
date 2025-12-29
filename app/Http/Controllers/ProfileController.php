<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return View The profile view
     */
    public function show(): View
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the authenticated user's profile.
     *
     * @return View The profile edit form view
     */
    public function edit(): View
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the authenticated user's profile in the database.
     *
     * @param  UpdateProfileRequest  $request  The validated request containing updated profile data
     * @return RedirectResponse Redirect to profile show with success message
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = auth()->user();

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        // Only update password if provided and old password is verified
        if ($request->filled('password')) {
            $user->password = $validatedData['password'];
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
