<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Secretary User',
            'email' => 'secretary@example.com',
            'password' => bcrypt('password'),
        ]);

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

        // Secretary has no default role permissions (will be assigned as direct permissions)
        $secretary->syncPermissions([]);

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $secretaryUser = User::where('email', 'secretary@example.com')->first();
        if ($secretaryUser) {
            $secretaryUser->assignRole('secretary');
            // Give default permissions as direct permissions (not role permissions)
            $defaultSecretaryPermissions = [
                'view_classes',
                'view_students',
                'generate_certificates',
                'generate_cards',
                'generate_attendance_lists',
            ];
            $secretaryUser->givePermissionTo($defaultSecretaryPermissions);
        }
    }
}
