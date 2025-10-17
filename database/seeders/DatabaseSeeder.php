<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\SchoolYear;
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
            'name' => 'Proviseur ',
            'email' => 'admin@gmail.com',
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

        // Give all permissions to admin
        $admin->syncPermissions(Permission::all());

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
        $secretary = Role::firstOrCreate(['name' => 'secretary']);

// Optionally, assign some permissions to secretary role
$secretaryPermissions = [
    'view_students',
    'create_students',
    'edit_students',
    'view_classes',
    'generate_certificates',
];

$secretary->syncPermissions($secretaryPermissions);
    }
}
