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
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        $secretaryUser = User::firstOrCreate(
            ['email' => 'secretary@example.com'],
            [
                'name' => 'Secretary User',
                'password' => bcrypt('password'),
            ]
        );

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

        // Assign roles to users
        if (! $adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }

        if (! $secretaryUser->hasRole('secretary')) {
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

        $schoolYears = SchoolYear::factory(3)->create();

        foreach ($schoolYears as $schoolYear) {
            Classe::factory(6)->create([
                'year_id' => $schoolYear->id,
            ]);
        }

        $classes = Classe::all();
        foreach ($classes as $classe) {
            Student::factory(2)->create([
                'classe_id' => $classe->id,
            ]);
        }
    }
}
