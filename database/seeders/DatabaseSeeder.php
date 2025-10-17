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
            'email' => 'admin@gmail.com',
            'name' => 'Proviseur ',
            'email' => 'admin@gmail.com',
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
@@ -80,32 +56,14 @@ public function run(): void

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $secretary = Role::firstOrCreate(['name' => 'secretary']);

        // Give all permissions to admin
        $admin->syncPermissions(Permission::all());

        // Secretary has no default role permissions (will be assigned as direct permissions)
        $secretary->syncPermissions([]);

        // Assign roles to existing users
        $adminUser = User::where('email', 'admin@gmail.com')->first();
        $adminUser = User::where('email', 'admin@gmail.com')->first();
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

}
