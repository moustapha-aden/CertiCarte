<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

/**
 * Controller for managing user roles and permissions.
 *
 * Handles role-based access control operations including
 * managing individual secretary permissions.
 *
 * @author Lycée Ahmed Farah Ali
 *
 * @version 1.0.0
 */
class RoleManagementController extends Controller
{
    /**
     * Update permissions for a specific secretary user.
     * Handles AJAX requests to toggle permissions.
     * Validates that the user has the 'secretary' role before updating.
     *
     * @param  Request  $request  The HTTP request containing permission data
     * @param  User  $user  The user to update permissions for
     * @return JsonResponse JSON response indicating success or failure
     *
     * @throws \Illuminate\Validation\ValidationException If validation fails
     */
    public function updatePermissions(Request $request, User $user): JsonResponse
    {
        // Ensure the user is a secretary
        if (! $user->hasRole('secretary')) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non autorisé',
            ], 403);
        }

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Sync permissions for the user
        $user->syncPermissions($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Permissions mises à jour avec succès',
            'permissions' => $user->permissions->pluck('name'),
        ]);
    }

    /**
     * Get permissions for a specific secretary user.
     * Used for AJAX requests to fetch current permissions.
     * Validates that the user has the 'secretary' role before retrieving.
     *
     * @param  User  $user  The user to get permissions for
     * @return JsonResponse JSON response with user permissions
     */
    public function getUserPermissions(User $user): JsonResponse
    {
        // Ensure the user is a secretary
        if (! $user->hasRole('secretary')) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non autorisé',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'permissions' => $user->permissions->pluck('name'),
        ]);
    }
}
