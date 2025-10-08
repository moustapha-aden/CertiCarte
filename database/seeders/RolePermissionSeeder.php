<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create granular permissions (CRUD-level)
        $permissions = [
            // User management - CRUD operations
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Class management - CRUD operations
            'view_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',

            // Student management - CRUD operations
            'view_students',
            'create_students',
            'edit_students',
            'delete_students',

            // Special functionalities
            'generate_certificates',
            'generate_cards',
            'generate_attendance_lists',
            'import_students',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);

        // Give all permissions to admin
        $admin->syncPermissions(Permission::all());

        // Secretary has default permissions (view + generate)
        $defaultSecretaryPermissions = [
            'view_classes',
            'view_students',
            'generate_certificates',
            'generate_cards',
            'generate_attendance_lists',
        ];
        $secretary->syncPermissions($defaultSecretaryPermissions);

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $secretaryUser = User::where('email', 'secretary@example.com')->first();
        if ($secretaryUser) {
            $secretaryUser->assignRole('secretary');
        }
    }
}
