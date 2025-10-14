<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    /**
     * Update permissions for a specific secretary user.
     *
     * @param  Request  $request  The HTTP request containing permission data
     * @param  User  $user  The user to update permissions for
     * @return JsonResponse JSON response indicating success or failure
     */
    public function updatePermissions(Request $request, User $user): JsonResponse
    {
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

        $user->syncPermissions($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Permissions mises à jour avec succès',
            'permissions' => $user->permissions->pluck('name'),
        ]);
    }

    /**
     * Get permissions for a specific secretary user.
     *
     * @param  User  $user  The user to get permissions for
     * @return JsonResponse JSON response with user permissions
     */
    public function getUserPermissions(User $user): JsonResponse
    {
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
