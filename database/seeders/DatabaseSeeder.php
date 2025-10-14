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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'Secretary User',
            'email' => 'secretary@example.com',
            'password' => bcrypt('password'),
        ]);

        $schoolYears = SchoolYear::factory(4)->create();

        // Create 20 classes total (5 classes per school year)
        foreach ($schoolYears as $schoolYear) {
            Classe::factory(5)->create([
                'year_id' => $schoolYear->id,
            ]);
        }

        $classes = Classe::all();

        // Ensure at least 45 students in each class
        foreach ($classes as $classe) {
            Student::factory(45)->create([
                'classe_id' => $classe->id,
            ]);
        }

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
